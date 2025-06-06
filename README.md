# SimpleAlts

**SimpleAlts** is a lightweight PocketMine-MP 5 plugin that tracks and controls alternative accounts (alts) based on player IPs. It lets you limit how many accounts can connect from the same IP and view all related accounts.

---

## 📦 Features

- 🌐 Stores data in either **local JSON** or **MySQL**.
- 🚫 Configurable alt account limit per IP.
- ❌ Option to automatically kick players exceeding the limit.
- 📋 `/alts` command to view accounts linked to an IP.
- ⚙️ Easy-to-edit `config.json` file.

---

## 🛠️ Requirements

- PocketMine-MP API 5.0.0 or higher

---

## 📂 Installation

1. Download or compile the plugin.
2. Place the .zip inside your `plugins/` directory and extract it.
3. Start the server.
4. Edit `plugins/SimpleAlts/resources/config.json` if needed.

---

## ⚙️ Configuration (`config.json`)

```json
{
  "mysql": {
    "enabled": false,
    "host": "localhost",
    "port": 3306,
    "database": "simplealts",
    "user": "root",
    "password": ""
  },
  "alts-limit": 3,
  "block-overlimit": true,
  "kick-message": "Too many accounts from your IP."
}
