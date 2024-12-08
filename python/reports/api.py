from flask import Flask, send_file, jsonify
from jinja2 import Environment, FileSystemLoader
from pdfkit import from_string
import requests
from datetime import datetime
import io

app = Flask(__name__)

# Функция для получения данных о событии
def fetch_data_from_api_event(id):
    BASE_API_URL = "https://final-hackathone.dev-level.ru/api/v1"
    event_api_url = f"{BASE_API_URL}/event/{id}"
    result_api_url = f"{BASE_API_URL}/result/{id}"
    response_event = requests.get(event_api_url)
    response_result = requests.get(result_api_url)

    if response_event.status_code == 200:
        data = response_event.json()
        date_from = datetime.strptime(data["date_from"], "%Y-%m-%d")
        formatted_date_from = date_from.strftime("%d.%m.%Y")
        date_to = datetime.strptime(data["date_to"], "%Y-%m-%d")
        formatted_date_to = date_to.strftime("%d.%m.%Y")

        date_now = datetime.now().strftime("%d.%m.%y")
        eventData = {
            "id": data["id"],
            "eventName": data["name"],
            "eventDescr": data["description"],
            "address": data["address"],
            "participants": data["participants"],
            "date_from": formatted_date_from,
            "date_to": formatted_date_to,
            "priority": data['priority'],
            "created_at": date_now,
            "winners_list": []
        }
        data_result = response_result.json()
        winner_list = []
        if data_result:
            for item in data_result:
                winner_list.append({
                    'winner_name': item["winner_name"],
                    'position': item["position"],
                    'score': item["score"],
                    "contacts": item["contact_info"]
                })
        
        eventData['winners_list'] = winner_list
        return eventData
    else:
        raise Exception(f"Ошибка при получении данных: {response_event.status_code}")


# Функция для получения данных о регионе (округе)
def fetch_data_from_api_okrug(i):
    BASE_API_URL = "https://final-hackathone.dev-level.ru/api/v1/regions"
    respons_ocrugs_events = requests.get(BASE_API_URL)
    if respons_ocrugs_events.status_code == 200:
        data = respons_ocrugs_events.json()
        result = next((item for item in data if item["id"] == i), None)
        
        date_now = datetime.now().strftime("%d.%m.%y")
        okrugData = {
            "created_at": date_now,
            "okrugName": f"{result['name']}",
            "eventsCount": "",
            'regions': result["regions"],
            "totalParticipants": "",
            'regionsInfo': [],
        }
        regions = []
        total_participants = 0
        total_events = 0
        for region in okrugData['regions']:
            regionOkrug = {
                'name': region['name'],
                'eventsCount': '',
                'regionParticipants': '',
                'eventsInfo': []
            }
            
            respons_region_events = requests.get(f"https://final-hackathone.dev-level.ru/api/v1/events/{region['id']}/")
            region_events = respons_region_events.json()
            regionOkrug['eventsCount'] = len(region_events)
            total_events += len(region_events)

            participantsCount = 0
            for event in region_events:
                e = {
                    "eventName": '',
                    "date_from": "",
                    "date_to": "",
                    "priority": "",
                    "participants": '',
                }
                date_from = datetime.strptime(event['date_from'], "%Y-%m-%d")
                formatted_date_from = date_from.strftime("%d.%m.%Y")
                date_to = datetime.strptime(event['date_to'], "%Y-%m-%d")
                formatted_date_to = date_to.strftime("%d.%m.%Y")
                e['eventName'] = event['name']
                e['date_from'] = formatted_date_from
                e['date_to'] = formatted_date_to
                e['priority'] = event['priority']
                e['participants'] = event['participants']
                regionOkrug['eventsInfo'].append(e)
                if event['participants'] is not None:
                    participantsCount += event['participants']
                    total_participants += event['participants']
                
            regionOkrug['regionParticipants'] = participantsCount
            regions.append(regionOkrug)

        okrugData['totalParticipants'] = total_participants
        okrugData['eventsCount'] = total_events
        okrugData['regionsInfo'] = regions
        return okrugData
    else:
        raise Exception(f"Ошибка при получении данных: {respons_ocrugs_events.status_code}")


# Функция для генерации PDF
def create_pdf(data, template_name):
    env = Environment(loader=FileSystemLoader('/var/www/fastuser/data/www/final-hackathone.dev-level.ru/python/reports/data'))
    template = env.get_template(template_name)
    html_out = template.render(data)

    options = {
        'page-size': 'A4',
        'margin-top': '10mm',
        'margin-right': '10mm',
        'margin-bottom': '10mm',
        'margin-left': '10mm',
        'encoding': 'UTF-8',
        'enable-local-file-access': ''
    }

    css_files = ['/var/www/fastuser/data/www/final-hackathone.dev-level.ru/python/reports/data/styles.css']
    file_content = from_string(html_out, options=options, css=css_files)
    return file_content


# API для генерации отчета по событию
@app.route('/generate_event_pdf/<int:event_id>', methods=['GET'])
def generate_event_pdf(event_id):
    try:
        event_data = fetch_data_from_api_event(event_id)
        pdf_file = create_pdf(event_data, 'report.html')
        
        pdf_io = io.BytesIO(pdf_file)
        pdf_io.seek(0)
        return send_file(pdf_io, as_attachment=True, download_name=f"event_{event_id}.pdf", mimetype="application/pdf")
    
    except Exception as e:
        return jsonify({"error": str(e)}), 400


# API для генерации отчета по округу
@app.route('/generate_okrug_pdf/<int:okrug_id>', methods=['GET'])
def generate_okrug_pdf(okrug_id):
    try:
        okrug_data = fetch_data_from_api_okrug(okrug_id)
        pdf_file = create_pdf(okrug_data, 'okrug.html')
        
        pdf_io = io.BytesIO(pdf_file)
        pdf_io.seek(0)
        return send_file(pdf_io, as_attachment=True, download_name=f"okrug_{okrug_id}.pdf", mimetype="application/pdf")
    
    except Exception as e:
        return jsonify({"error": str(e)}), 400


if __name__ == '__main__':
    app.run(debug=True, port=5002)
