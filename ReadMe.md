# ReadMe

- Install composer, from https://docs.phpcomposer.com/00-intro.html#Globally.
    - Check windows installer, Composer-Setup.exe

- Create a new directory php-rdf
- Create composer.json in root of php-rdf
- Run 'composer install'

- Write demo.php to test

- 使用欧元的国家有哪些？
PREFIX  : <http://www.DreamTravel.com/>
SELECT *
WHERE {
  ?s :Trade_in :Euro
}
LIMIT 10

- 使用英语的国家有哪些？
PREFIX  : <http://www.DreamTravel.com/>
SELECT *
WHERE {
  ?s :use :English
}
LIMIT 600

- 最受欢迎的沿海城市有哪些？
SELECT *
WHERE {
  ?City :Number_of_tourists ?o.
  ?City ?p :Coastal_City
}
LIMIT 600

- 旅游人次最高的城市属于哪个国家？
PREFIX  : <http://www.DreamTravel.com/>
SELECT *
WHERE {
	?c :isCityOf ?Country.
	?c :Number_of_tourists ?o
}
LIMIT 600
