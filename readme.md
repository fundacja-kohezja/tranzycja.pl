# Instrukcja obsługi

## Struktura folderów

Wszystkie pliki przewidziane do edycji znajdują się w folderze **source**, w podfolderach, których nazwy zaczynają się od **jednego znaku podkreślnia** (pozostałe stanowią strukturę strony i ich zmiana może sprawić, że strona się nie zbuduje).

W momencie zmiany lub dodania nowego pliku, strona automatycznie się zbuduje i będzie od razu widać na niej zmiany.

Folder **\_ogolne** zawiera treść występującą na stronie głównej i w różnych innych miejscach witryny. Te pliki można swobodnie edytować, ale lepiej ich nie usuwać, nie ma też po co dodawać tam nowych.

Natomiast pliki w folderach **\_aktualnosci**, **\_strony**, **\_publikacje** i **\_krok_po_kroku** można dowolnie dodawać, edytować i usuwać. Odpowiadają one  treściom widocznym na stronie w odpowiednich sekcjach.
Pliki z folderu **\_strony**, są podstronami, których nie ma nigdzie wylistowanych, ale można np. wstawić gdzieś do nich link.
Jest jeszcze folder **\_media** - przewidziałem go do wrzucania obrazków, które potem można wstawić gdzieś do treści.

## Format plików, z których budowana jest treść

Wszytskie pliki w ww. folderach są plikami markdown. Aby jak najbardziej uprościć proces dodawania treści, do plików markdown nie trzeba dopisywać żadnego frontmattera na początku. Nie trzeba określać żadnych metadanych, sama treść w zupełności wystarczy (wyjątek stanowi sekcja *krok po kroku*, gdzie we frontmatterze określa się kolejność, by artykuły wyświetlały się w określonym porządku). Takie rzeczy jak tytuł czy spis treści są automatycznie wyciągane z nagłówków, a data wpisu też jest dodawana automatycznie.
