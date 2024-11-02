# Wypożyczalnia sprzętu narciarskiego "Slalom" - Projekt bazy danych

## Autorzy
- **Bartłomiej Kuk** - Lider zespołu (Nr albumu: 272497)
- **Daria Siwiak** (Nr albumu: 272510)

## Opis Projektu
Celem projektu jest stworzenie aplikacji bazodanowej dla wypożyczalni sprzętu narciarskiego "Slalom" we Wrocławiu. Aplikacja została zaprojektowana z myślą o dwóch typach użytkowników: administratorze i użytkowniku końcowym.

## Funkcjonalności

### Strona administratora
- Wyświetlanie danych klientów
- Dodawanie nowego sprzętu do bazy
- Zarządzanie stanem magazynowym (wyświetlanie i zmiana ilości produktów)
- Aktualizacja cen produktów

### Strona użytkownika końcowego
- Rejestracja i modyfikacja danych konta
- Rezerwacja sprzętu oraz umawianie odbioru
- Przegląd historii transakcji
- Sprawdzanie dostępności sprzętu

### Cechy wspólne
- Logowanie i wylogowanie
- Zmiana hasła

### Cechy niefunkcjonalne
- Ochrona danych przed odczytaniem w przypadku wycieku
- Zabezpieczenie przed SQL Injection
- Responsywność aplikacji

## Technologie
- **Backend**: PHP
- **Baza danych**: MySQL
- **Frontend**: HTML, CSS, JavaScript