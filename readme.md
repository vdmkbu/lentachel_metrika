### description
api для получения данных из яндекс метрики для сайта lentachel.ru

### API для статистики
#####Получаем данные по посетителям за последние 12 месяцев
```http request
GET /api/metrika/stat/get/visits
``` 
![Альтернативный текст](https://lentachel.ru/netcat_files/userfiles/static/stat.png)  

=======  
  
#####получаем данные по долгосрочным интересам за последние 12 месяцев
```http request
GET /api/metrika/stat/get/interes
```    
![Альтернативный текст](https://lentachel.ru/netcat_files/userfiles/static/interes.png?1559409539)

=======

#####получаем данные по полу посетителей за последние 12 месяцев
```http request
GET /api/metrika/stat/get/gender
```  
![Альтернативный текст](https://lentachel.ru/netcat_files/userfiles/static/gender.png?1559409536)

=======
#####получаем данные по возрасту посетителей за последние 12 месяцев
```http request
GET /api/metrika/stat/get/age
```
![Альтернативный текст](https://lentachel.ru/netcat_files/userfiles/static/age.png?1559409534)

=======
### API для рассылки
#####получаем json с самыми просматриваемыми новостями на текущей неделе
```http request
GET /api/metrika/subscribe/weekly
```

======
#####получаем json с самыми просматриваемыми новостями за текущий день
```http request
GET /api/metrika/subscribe/daily
```

======  

### API для отчётов
##### сохраняем в БД отчёт по кол-ву посетителей каждого URL для среза start - end
```http request
GET /api/metrika/report/author/store
```
параметры:   
**start** - начало среза yyyy-mm-dd  
**end** - конец среза yyyy-mm-dd  

======
##### получаем кол-во посетителей для адреса url
```http request
GET /api/metrika/report/author/get
```
параметры:  
**url** - искомый URL /yyyy/mm/dd/keyword  

=====
##### очищаем данные в БД для последнего отчёта по url'ам
```http request
GET /api/metrika/report/author/reset
```

=====

##### сохраняем в БД отчёт по кол-ву просмотров каждого заголовка title для среза start - end
```http request
GET /api/metrika/report/title/store
```
параметры:   
**start** - начало среза yyyy-mm-dd  
**end** - конец среза yyyy-mm-dd  

=====
##### Получаем кол-во просмотров для заголовка новости title
```http request
GET /api/metrika/report/title/get
```
параметры:  
**title** - заголовок новости  

=====
##### очищаем данные в БД для последнего отчёта по заголовкам
```http request
GET /api/metrika/report/title/reset
```