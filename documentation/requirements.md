## Reikalavimai
**Funkciniai reikalavimai**
| Nr. | Reikalavimas | Matas | MoSCoW |
|---|---|---|---|
| FR1 | Darbuotojas gali sukurti užklausą | Veikia per formą su validavija | M |
| FR2 | Darbuotojas gali redaguoti užklausą | Galimas kol UNVLAIMED | S |
| FR3 | Darbuotojas gali pašalinti užklausą | Sunaikinamas įrašas iš DB | M |
| FR4 | Vartotojas gali filtruoti užklausas pagal statusą | SQL užklausa per GET | S |
| FR5 | Vartotojas gali filtruoti užklausas pagal prioritetą | SQL užklausa per GET | S |
| FR6 | Supportas gali prisiimti užklausą (UNCLAIMED -> CLAIMED) | Pakeičiamas statusas ir prirašomas SupporterID | M |
| FR7 | Vartotojas gali komentuoti užklausoje | Pokalbių stiliaus sąsaja | M |
| FR8 | Vartotojas gali užbaigti užklausą (CLAIMED -> SOLVED) | Pakeičiamas statusas | M |

**Nefunkciniai reikalavimai**
| Nr. | Reikalavimas | Matas | MoSCoW |
|---|---|---|---|
| NFR1 | Apsauga nuo SQL injekcijų ir XSS | Naudojami PDO paruošti teiginiai | M |
| NFR2 | Responyvus dizainas | Pritaikyta su Bootstrap 5 | M |
| NFR3 | Greitas užklausų atvaizdavimas | Puslapis kraunasi greičiau nei per 1 sek. net turint tūkstantį ar daugiau įrašų bazėje | S |

**User stories su priimtinumo kriterijais**
| Nr. | Vaidmuo | Veiksmas | Nauda |
|---|---|---|---|
| US1 | Kaip darbuotojas | Aš noriu sukurti naują IT pagalbos užklausą nurodant pavadinimą, prioritetą ir aprašymą | Kad galėčiau pranešti apie iškilusią techninę problemą |
| US2 | Kaip darbuotojas | Aš noriu redaguoti savo užklausą, kol jos statusas yra UNCLAIMED | Kad galėčiau pataisyti klaidas ar patikslinti informaciją |
| US3 | Kaip darbuotojas | Aš noriu ištrinti savo užklausą | Kad galėčiau pašalinti klaidingai sukurtą ar nebereikalingą kreipimąsi |
| US4 | Kaip vartotojas (darbuotojas / support) | Aš noriu filtruoti užklausas pagal statusą ir prioritetą | Kad galėčiau greitai rasti ir valdyti dominančius įrašus |
| US5 | Kaip support darbuotojas | Aš noriu peržiūrėti laisvas užklausas ir jas prisiimti sau (UNCLAIMED -> CLAIMED) | Kad galėčiau pradėti spręsti vartotojo problemą |
| US6 | Kaip vartotojas | Aš noriu rašyti ir matyti komentarus prie užklausos (pokalbių stiliaus sąsajoje) | Kad galėčiau efektyviai bendrauti ir keistis informacija sprendžiant problemą |
| US7 | Kaip support darbuotojas | Aš noriu užbaigti prisiimtą užklausą (CLAIMED -> SOLVED) | Kad galėčiau pažymėti problemą kaip sėkmingai išspręstą |