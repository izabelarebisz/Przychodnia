# Doctor Appointment System
Aplikacja mobilna webowa umożliwiająca zapisanie się przez pacjenta na wizytę u wybranego lekarza w jednym z dostępnych terminów. Weryfikacja użytkowników następuje przez przeszukanie bazy sql zawierającej dane wraz z zaszyfrowanymi hasłami, więc jeśli nie posiadamy jeszcze konta klikamy w "zarejestruj się" i podajemy niezbędne dane.

## Sposób działania


![alt text](https://github.com/izabreb808/DoctorAppointmentSystem/blob/main/pliki%20do%20dokumentacji/schemat.png)


* index.php – okno logowania do aplikacji, należy wpisać login i hasło
(strona nie przepuści nas dopóki nie podamy prawidłowych danych).
Następuje przeszukanie bazy danych w celu ustalenia czy użytkownik
posiada swoje konto.
* zarejestruj.php – okno rejestracji, należy podać wszystkie niezbędne
dane, strona jest zabezpieczona: imię i nazwisko nie mogą pozostać
puste, pesel musi zawierać 11 znaków, login nie może znajdować się już
w bazie danyh, hasło musi składać się z 5–20 znaków i zostać prawidłowo powtórzone w kolejnym polu.
* strona_glowna.php – strona na której znajduje się kalendarz oraz lista
lekarzy zatrudnionych w przychodni. Użytkownik wybiera interesujący
go termin oraz lekarza i klika w przycisk przenoszący go do kolejnej
strony.
* godzina.php – z bazy pobierane są rekordy w których występuje wybrany wcześniej lekarz i jednocześnie wybrana data. Program znajduje
godziny, które dany lekarz w danym dniu ma już zarezerwowane i udostępnia listę pozostałych. Użytkownik wybiera dowolną z proponowanych godzin i jeśli nie zostanie w bazie znaleziony jako umówiony już
na wizytę do wybranego lekarza to zostaje dopisany do tabeli wizyty.
* zaloguj.php i wyloguj.php – pliki w których wykonywane są operacje
logowania i wylogowania z aplikacji. Hasła są hashowane, dodatkowo
logowanie jest zabezpieczone przed tzw. wstrzykiwaniem sql.
Dodatkowo przy każdym uruchomieniu baza danych jest aktualizowana i
usuwane są wizyty, które się już odbyły.

## Użyte środki
Aplikacja została napisana w języku HTML natomiast funkcjonalności zostały zaimplementowane w języku PHP. Korzysta ona z bazy danych
„przychodnia_baza.sql”. Dodatkowo użyte zostały biblioteki, takie jak:
* bootstrap – biblioteka CSS, która umożliwiła dodanie m. in. modalu
oraz kalendarza.
* jQuery – biblioteka JavaScriptu, która została wykorzystana do spersonalizowania kalendarza oraz zablokowania dat 31 dni do przodu, a
także tych minionych.

Baza danych składa się z 3 tabeli:

![alt text](https://github.com/izabreb808/DoctorAppointmentSystem/blob/main/pliki%20do%20dokumentacji/pacjenci.png)

![alt text](https://github.com/izabreb808/DoctorAppointmentSystem/blob/main/pliki%20do%20dokumentacji/lekarze.png)

![alt text](https://github.com/izabreb808/DoctorAppointmentSystem/blob/main/pliki%20do%20dokumentacji/wizyty.png)

