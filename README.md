# Notes Service

RESTful API сервис заметок (CRUD) на Laravel 11 и Vue.js 3. Контейнеризация через Docker, развёртывание одной командой.

## Развёртывание одной командой

После клонирования репозитория достаточно выполнить:

```bash
docker-compose up -d --build
```

Файл `.env` создаётся автоматически из `.env.example` при первом запуске контейнера. Приложение будет доступно по адресу: **http://localhost:8080**

При первом запуске entrypoint автоматически:
- устанавливает зависимости Composer (если нет `vendor`);
- копирует `.env.example` в `.env` при отсутствии `.env`;
- генерирует `APP_KEY`;
- выполняет миграции БД;
- при отсутствии `public/build/manifest.json` выполняет `npm install` и `npm run build` (первый запуск с volume может занять 1–2 минуты).

База данных: MySQL 8, данные сохраняются в volume `notes-db-data`.

## Переменные окружения (.env)

Переменные в `.env.example` соответствуют конфигурации Docker:

| Переменная   | Описание        | По умолчанию (Docker) |
|-------------|-----------------|------------------------|
| DB_HOST     | Хост БД         | db                     |
| DB_DATABASE | Имя БД          | notes                  |
| DB_USERNAME | Пользователь БД | notes                  |
| DB_PASSWORD | Пароль БД       | secret                 |
| APP_URL     | URL приложения  | http://localhost:8080  |

## API

Базовый путь: `/api/notes`.

| Метод   | Путь           | Описание          |
|--------|-----------------|-------------------|
| GET    | /api/notes      | Список заметок    |
| GET    | /api/notes/{id} | Одна заметка      |
| POST   | /api/notes      | Создать заметку   |
| PUT    | /api/notes/{id} | Обновить заметку  |
| DELETE | /api/notes/{id} | Удалить заметку   |

### Формат тела запроса (POST / PUT)

```json
{
  "title": "string (обязательно, макс. 255)",
  "content": "string (обязательно)"
}
```

### Ответы

- Успех: JSON с полями `id`, `title`, `content`, `created_at`, `updated_at`.
- Список: `{ "data": [ ... ] }`.
- Ошибка валидации: 422, `{ "message": "...", "errors": { "field": ["..."] } }`.
- Не найдено: 404, `{ "message": "Note not found" }`.
- Удаление: 204 без тела.

## Архитектура

- **Backend**: Laravel 11, PHP 8.3. Слой сервисов (`NoteService`) для бизнес-логики, «тонкие» контроллеры. Валидация через FormRequest, ответы через JsonResource.
- **Frontend**: Vue 3 (Composition API), Vite, TailwindCSS. Компоненты: `NoteList`, `NoteForm`, `NoteItem`. API-клиент на Axios с обработкой ошибок.
- **Docker**: сервисы `app` (PHP-FPM + Node для сборки фронта), `nginx`, `db` (MySQL). Volume для персистентности БД. Фронтенд собирается при выполнении `docker-compose up -d --build` (на этапе сборки образа) и при первом старте контейнера, если volume перезаписал `public/build`.

