# Tranzycja.pl

Niniejsze repozytorium zawiera treść oraz kod witryny tranzycja.pl – polskiego kompedium wiedzy dotyczącej transpłciowości.

## Treść witryny

Treść witryny jest budowana z plików markdown. Zmiana tych plików w repozytorium powoduje przebudowanie strony, dzięki czemu po kilku minutach od edycji zmiany powinny być widoczne na https://tranzycja.pl.

Wspomniane pliki markdown znajdują się w kilku folderach – każdy z nich odpowiada za inny rodzaj treści:

* [Krok po kroku](source/_krok_po_kroku/) – wpisy przeprowadzające krok po kroku przez różne aspekty procesu tranzycji, wylistowane w wg pola `kolejnosc` we frontmatterze
* [Publikacje](source/_publikacje/) – artykuły, poradniki, eseje, świadectwa, tłumaczenia, opisy doświadczeń i inne dłuższe publikacje na rożne tematy związane z tranzycją, wylistowane od najnowszych
* [Publikacje EN](source/_publications/) – jak wyżej, ale obcojęzyczne, na razie nie wylistowane, ale można je zalinkować
* [Aktualności](source/_aktualnosci/) – krótkie formy informacyjne dotyczące bieżących wydarzeń lub rozwoju projektu, wylistowane od najnowszych
* [Strony](source/_strony/) – dodatkowe podstrony, nie są nigdzie wylistowane, ale można je zalinkować, np. w stopce
* [Wsparcie](source/_wsparcie/) – wpisy przedstawiające różne możliwości wsparcia projektu tranzycja.pl, wylistowane w wg pola `kolejnosc` we frontmatterze

Każdy plik odpowiada pojedynczej podstronie, jego nazwa jest końcówką adresu url, a tytuł jest automatycznie wyciągany z nagłówka 1. poziomu (dlatego każdy plik powinien mieć dokładnie jeden taki nagłówek). Z nagłówków jest też automatycznie generowany spis treści (nie dotyczy stron i aktualności).

Do prawidłowego zbudowania strony pliki markdown nie wymagają frontmattera, można go jednak użyć, by umieścić w nim różne metadane danego wpisu (np tagi, autora…). Daty publikacji dodają się automatycznie przy dodawaniu plików do repozytorium, więc ich również nie trzeba uzupełniać ręcznie. Należy jedynie zwracać uwagę przy edycji już opublikowanych wpisów – jeśli we frontmatterze widnieje już data ostatniej aktualizacji, powinniśmy ją usunąć przy edycji, by podczas budowania wygenerowała się nowa – aktualna.

Jest jeszcze folder [Ogólne](source/_ogolne/), gdzie można zmienić:
- tytuł i opis witryny
- tekst wprowadzający na stronach z wylistowanymi artykułami
- sekcję wstępu i FAQ na stronie głównej
- stopkę witryny

Treść strony jest dostępna na licencji CC BY-SA 3.0 PL ([więcej](https://tranzycja.pl/wsparcie/licencja/)).

## Kod witryny

Cała reszta repozytorium poza wymienionymi wyżej folderami stanowi kod strony, z którego generowana jest statyczna witryna. Generatorem statycznej witryny jest [Jigsaw](https://jigsaw.tighten.com), rozszerzony o dodatkowe funkcjonalności.

Kod strony podlega licencji GPLv3.
