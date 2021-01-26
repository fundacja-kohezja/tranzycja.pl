# Instrukcja obsługi

Wszystkie pliki przewidziane do edycji znajdują się w folderze *source*, w podfolderach, których nazwy **zaczynają się od znaku podkreślnia** (pozostałe stanowią strukturę strony i ich zmiana może sprawić, że strona się nie zbuduje).

W momencie zmiany lub dodania nowego pliku, strona automatycznie się zbuduje i będzie od razu widać na niej zmiany.

Folder **\_ogolne** zawiera treść występującą na stronie głównej i w różnych innych miejscach witryny. Te pliki można swobodnie edytować, ale lepiej ich nie usuwać, nie ma też po co dodawać tam nowych.

Natomiast pliki w folderach **\_aktualnosci**, **\_strony** i **\_poradniki** można dodwać, edytować i usuwać.

Jak sama nazwa wskazuje, pliki w folderze **\_aktualnosci** odpowiadają aktualnościom widocznym w sekcji *Aktualności* na stronie, a w folderze **\_poradniki** odpowiadają poradnikom widocznym w sekcji *Poradniki*.
Pliki z folderu **\_strony**, są podstronami, których nie ma nigdzie wylistowanych, ale można np. wstawić gdzieś do nich link.

Wszytskie pliki w ww. folderach są plikami markdown. Jako, że chciałem jak najbardziej uprościć proces dodawania treści, to zrobiłem tak, że do plików markdown nie trzba dopisywać żadnego frontmattera na początku. Nie trzeba określać żadnych metadanych, sama treść w zupełności wystarczy. Takie rzeczy jak tytuł czy spis treści są automatycznie wyciągane z nagłówków. (Btw, prawdopodobnie odkryłem przyczynę dla której nie działały te linki do sekcji, jak się użyło polskich znaków, w tamtym Jekyllowym generatorze)

Jest jeszcze folder **\_media** - przewidziałem go do wrzucania obrazków, które potem można wstawić gdzieś do treści.
