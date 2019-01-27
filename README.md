# Navia
Sustav za potporu edukacijskom osoblju. Cilj projekta je profesorima pružiti jedinstveno sučelje kojim imaju uvid u sve aspekte vođenja nastave (raspoloženje, pitanja, komentari). Ovaj sustav se može koristiti samostalno ali i uz već postojeće sustave online učionica kako bi se kvaliteta same nastave maksimalno poboljšala.

# Instalacija

### Potreban software

 - Apache 2
 - MySQL 5
 - PHP 7
 - Composer

Na Windowsima postoji WAMP alat (LAMP za Linux) koji dolazi sa svime potrebnim za lokalnu produkciju. Composer nije uključen.
Ukoliko dođe do neke pogreške prilikom instalacije WAMP-a, vjerojatno neki od servisa neće raditi (MySQL). Pogledati poglavlje Korištenje WAMP-a za rješenje.

### Dohvaćanje projekta sa githuba

```git clone https://github.com/dashydasher/Navia```

Ukoliko je projekt dohvaćen s githuba, fali mu vendor folder koji je potreban zbog projektih dependencyja. Za to je potrebno skinuti Composer alat i prilikom instalacije paziti da se odabere PHP verzija 7. Nakon toga potrebno se u konzoli pozicionirati u projektni folder i instalirati sve dependencyje. Komanda za to je ```composer install``` nakon čega se pojavljuje vendor folder. Inače je u datoteci ```composer.json``` vidljivo koji sve dependency su potrebni (koristimo samo Twig).

### Korištenje WAMP-a

 - Kad se WAMP upali bitno je da se pojavi zelena ikonica što znači da rade Apache, MySQL i PHP. Ako je samo naranđasta ikonica onda vjerojatno MySQL ne radi. To znači da se ne može spajati na lokalnu bazu. Problem se može rješiti tako da se instaliraju svi Microsoft Visual C++ Redistributable (i x86 i x64) sve od 2008 pa do 2017 i nakon toga ponoviti instalaciju WAMP-a (potražiti rješenje i online). Nažalost trenutno nema boljeg načina.
 - Također, možda se treba ugasiti Skype jer neki od servisa koristi isti port.
 - Postaviti WAMP da radi s PHP-om verzije 7: Lijevi klik na WAMP ikonicu --> PHP --> Version --> odabrati bilo koju verziju 7 (to je potrebno samo jednom napraviti).

### Kreiranje baze za lokalnu produkciju

S upaljenim WAMP-om potrebno je u browseru otiči na ```http://localhost/phpmyadmin/``` i prijaviti se (u: "root", p: ""). Bazu je najlakše kreirati tako da se stisne na ```SQL``` karticu gore i kopira MySQL skripta za kreiranje baze (u skripti je ime baze "navia_fer").

### Postavljanje konfiguracije baze

Unutar projektnog foldera nalazi se folder config u kojem se treba nalaziti datoteka ```database.php``` koja je isključena s gita. U njoj se postavljaju parametri za bazu. Za lokalnu produkcije u file unesite ovo:

```
<?php
return (object) array(
    "servername" => "localhost",
    "username" => "root",
    "password" => "",
    "db" => "navia_fer",
    "charset" => "utf8",
);
```
