1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/yourusername/sensor-api.git
2. Установите зависимости
3. Настройте БД (Я использовал SQLite)
4. Выполните миграции и сидеры
5. Отправка данных от датчиков:
   GET /api?sensor=<sensor_id>
   Body: <parameter>=<value>
6. Получение данных из БД:
   GET /api/data?sensor=<sensor_id>&parameter=<parameter>&start_date=<start_date>&end_date=<end_date>
