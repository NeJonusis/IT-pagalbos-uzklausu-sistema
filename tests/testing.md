## Testavimas
| Nr. | Testas | Kas buvo daryta | Ko tikėtasi | Kas atsitiko | Kaip sutvarkyta |
|---|---|---|---|---|---|
| 1 | Užklausos redagavimas | Title/Description pakeitimas į " " | Sistema neleis tuščios vietos | Sistema pakeitė Title/Description į " " | Pridėta papildoma (trim()) apsauga nuo " " |
| 2 | Bandymas prisijungti prie sistemos, išjungus DB | Išjungtas MySQL, bandymas prisijungti kaip vartotojas | Sistema informuos vartotoją ir išsaugos klaidą | Sistema išmetė klaidą | Pridėtas vartotojo informavimo ir klaidos įrašymo į logs.txt kodas |
