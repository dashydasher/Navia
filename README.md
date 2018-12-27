# Navia
Sustav za potporu edukacijskom osoblju. Cilj projekta je profesorima pružiti jedinstveno sučelje kojim imaju uvid u sve aspekte vođenja nastave (raspoloženje, pitanja, komentari). Ovaj sustav se može koristiti samostalno ali i uz već postojeće sustave online učionica kako bi se kvaliteta same nastave maksimalno poboljšala.

# Docs

## Session varijable za studenta

### SESSION["current_moods"]
pamti studentova raspoloženja za svaku sobu di je ušao, a to služi za prikaz zadnjeg raspoloženja studentu kad izađe iz sobe pa se ponovo vrati u nju.
također je i bitno za pamćenje je li to raspoloženje inicijalno (student se tek pridružio sobi) ili je već mijenjano.
ključ je ID sobe, a vrijednosti su samo neki atributi klase mood (id, mood_option_id) definirani u modelu.
```
'current_moods' =>
    array (size=2)
      1 =>
        object(Models\Mood)[3]
          public 'id' => string '3' (length=1)
          public 'mood_option_id' => string '2' (length=1)
      2 =>
        object(Models\Mood)[2]
          public 'id' => string '4' (length=1)
          public 'mood_option_id' => string '2' (length=1)
```

### SESSION["entered_room_id"]
pamti id sobe u kojoj je student trenutno. služi za dodavanje komentara, pitanja i promjene raspoloženja.
```
'entered_room_id' => int 2
```

## Session varijable za profesora

### SESSION["my_id"]
ID teachera.
```
'my_id' => int 10
```

### SESSION["my_name"]
služi za prikaz imena u headeru(navbaru).
```
'my_name' => string 'Petar Jalušić' (length=15)
```

### SESSION["room_id"]
id sobe u kojoj sam trenutno.
```
'room_id' => string '2' (length=1)
```
