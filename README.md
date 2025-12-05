# Яндекс Карты - Парсинг отзывов

### 1. Запуск через Docker

```bash
# Скопировать конфигурацию
copy .env.example .env

# Запустить контейнеры
docker-compose up -d

# Установить зависимости
docker-compose exec app composer install
docker-compose exec app npm install

# Создать БД и пользователя
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed

# Собрать фронт
docker-compose exec app npm run build
```

### 2. Вход

**Данные для входа:**
- Email: `admin@example.com`
- Пароль: `password`

### 3. Настройка

1. Перейдите в раздел **"Настройка"**
2. Вставьте ссылку на организацию с Яндекс Карт
   - Пример: `https://yandex.ru/maps/org/usadba_izmaylovo/226327670406/`
3. Нажмите **"Сохранить"**
4. Перейдите в раздел **"Отзывы"** для просмотра