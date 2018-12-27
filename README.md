# Navia
Sustav za potporu edukacijskom osoblju. Cilj projekta je profesorima pružiti jedinstveno sučelje kojim imaju uvid u sve aspekte vođenja nastave (raspoloženje, pitanja, komentari). Ovaj sustav se može koristiti samostalno ali i uz već postojeće sustave online učionica kako bi se kvaliteta same nastave maksimalno poboljšala.

# $_SESSION["current_mood"]
pamti studentova raspoloženja za svaku sobu di je ušao, a to služi za prikaz zadnjeg raspoloženja studentu kad izađe iz sobe pa se ponovo vrati u nju.
također je i bitno za pamćenje je li to raspoloženje inicijalno (student se tek pridružio sobi) ili je već mijenjano.
ključ je ID, a vrijednosti su samo neki atributi klase mood (mood_option_id, room_id) definirane u modelu
```
'current_mood' =>
    array (size=1)
      30 =>
        object(Models\Mood)[3]
          public 'mood_option_id' => string '2' (length=1)
          public 'room_id' => int 1
```
