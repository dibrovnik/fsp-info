# File: notification_bot.py

import asyncio
import sqlite3
from aiogram import Bot, Dispatcher, types
from aiogram.types import ParseMode
from aiogram.utils import executor
from fastapi import FastAPI, HTTPException
from uvicorn import Config, Server
from pydantic import BaseModel

# Bot token from BotFather
TELEGRAM_BOT_TOKEN = "8041650496:AAEAf1fdeofjTS-WI8g6F2b8EgyZiyfv8c8"

# Initialize bot and dispatcher
bot = Bot(token=TELEGRAM_BOT_TOKEN)
dp = Dispatcher(bot)

# Initialize FastAPI
app = FastAPI()

# Database setup
DB_FILE = "users.db"

def init_db():
    """Initialize the database and create the necessary table."""
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS users (
            agent_id INTEGER PRIMARY KEY,
            chat_id INTEGER NOT NULL UNIQUE
        )
        """)
        conn.commit()

init_db()

# FastAPI Notification model
class Notification(BaseModel):
    agent_id: int
    message: str

# Telegram bot handlers
@dp.message_handler(commands=["start"])
async def start_handler(message: types.Message):
    """Handles the /start command with optional agent_id parameter."""
    args = message.get_args()
    if not args.isdigit():
        await message.reply("Invalid start parameter. Please contact support.")
        return

    agent_id = int(args)
    chat_id = message.chat.id

    # Save agent_id and chat_id to database
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        try:
            cursor.execute("INSERT INTO users (agent_id, chat_id) VALUES (?, ?)", (agent_id, chat_id))
            conn.commit()
            await message.reply("Account successfully linked! 🎉")
        except sqlite3.IntegrityError:
            await message.reply("This account is already linked.")

@app.post("/send-notification/")
async def send_notification(notification: Notification):
    """Send a notification to a user via Telegram."""
    with sqlite3.connect(DB_FILE) as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT chat_id FROM users WHERE agent_id = ?", (notification.agent_id,))
        result = cursor.fetchone()

    if not result:
        raise HTTPException(status_code=404, detail="User not found")

    chat_id = result[0]
    try:
        await bot.send_message(chat_id=chat_id, text=notification.message, parse_mode=ParseMode.HTML)
        return {"status": "success", "message": "Notification sent"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Failed to send notification: {str(e)}")

# Run both FastAPI and aiogram
async def main():
    # Настройка конфигурации для Uvicorn
    config = Config(app, host="0.0.0.0", port=8000)
    server = Server(config)

    # Запуск FastAPI в текущем событийном цикле
    loop = asyncio.get_event_loop()
    loop.create_task(server.serve())

    # Запуск aiogram
    await dp.start_polling()

if __name__ == "__main__":
    asyncio.run(main())
