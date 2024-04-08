# Управление пиццерией: Backend API 🍕

Добро пожаловать в проект "Управление пиццерией"! Этот API предоставляет возможность управления заказами, клиентами, меню и другими аспектами работы пиццерии.

## Запуск проекта

Прежде чем начать использовать API, убедитесь, что у вас установлен Docker и Docker Compose.

1. Откройте терминал и выполните следующие команды:

```bash
make start-dev
```

2. После успешного старта docker-compose выполните:

```bash
make build-bd
```


Эта команда создаст необходимую таблицу в базе данных. Если вы не меняли пароль, используйте пароль "qwerty".

Теперь API готово к использованию по следующему URL: http://localhost:8876.

Документация API
Вы можете ознакомиться с документацией API, перейдя по следующей ссылке: http://localhost:8876/swagger. Здесь вы найдете описание всех доступных методов, их параметры и примеры использования.



P.S. Написал все без использования библиотек или фреймворков, воспринял фразу "Задание желательно выполнить без использования фреймворков или допускается использовать отдельные их компоненты или библиотеки..." как челлендж
Со swagger'ом знакомился в процессе выполнения задания.