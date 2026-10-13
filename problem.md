## Problema
Organizacijos IT skyrius neturi fiksuotos problemų sprendimo sistemos/vietos, todėl užklausos teikiamos el. paštu, telefonu, žinutėmis. Dėl sistemos nebuvimo, dalis užklausų pasimeta, lieka neaiškios ar nežinomas atsakingas asmuo.

## Naudotojai
**Darbuotojas**
- Ką daro: Registruoja iškilusias IT problemas, stebi jų būseną, teikia papildomus komentarus.
- Labiausiai nori: Lengvai ir greitai pateikti užklausą, matyti problemos būseną.

**IT specialistas**
- Ką daro: Peržiūri užklausas, paskiria jas sau ar kitiems IT specialistams, rašo komentarus ir žymi užklausos būseną.
- Labiausiai nori: Matyti aiškų užklausų sąrašą, jų prioritetus.

## Esamas procesas (as-is)
**Kur dabar saugoma**
El. paštas, žinutės, pokalbiai.
**Proceso žingsniai**
1.	Darbuotojas pastebi problemą.
2.	Darbuotojas apie problemą praneša IT specialistui per el. paštą, skambutį ar žinute.
3.	IT specialistas problemos žinutę gauną, ją perskaito arba pamiršta, bando spręsti arba persiunčia kitam IT specialistui.
**Kur gaižta laikas**
Atsakingo asmens paieškos, kas problemą priėmė, o kas sprendė, jei išvis kas sprendė.
**Kas atsitinka, kai kažkas pasimeta**
Problema lieka neišspręsta, darbuotojas negali atlikti arba sunkiau atlieka savo darbo funkcijas.

## Norimas pokytis (to-be)
- Visos IT problemų užklausos patenka į vieną sistemą, pašalinant pasimetusių užklausų šansą.
- Užklausos priimimas ir sprendimo pateikimas vykdomas greičiau.
**Ką matuosime**
Visos užklausos ir jų būsenos vienoje sistemoje. Užklausos priskyrimo laikas sutrumpėja.
**Ribos (ko NEDARYSIME)**
Nekursime mobilios programėlės
Per 2 savaites kokybiškai nespėsime.

## Analogų palyginimas
| Analogas                  | 3 pagrindinės funkcijos                       | Kas naudinga/kas ne
|---------------------------|-----------------------------------------------|--------------------
| Jira Service Management   | 1. Užklausų registravimas ir registras.       |Naudinga: Puikus užklausų filtravimas ir būsenų valdymas.
|                           | 2. Automatizuotas priskyrimas ir SLA sekmis.  |Nenaudinga: Per daug sudėtinga konfigūracija smulkiam verslui.
|                           | 3. Žinių bazė.                                |
|---------------------------|-----------------------------------------------|--------------------
| Freshdesk                 | 1. Bilietų priskyrimas agentams.              |Naudinga: Intuityvus sąsajos valdymas ir aiškūs statusai.
|                           | 2. Prioritetų nustatymas ir žymės.            |Nenaudinga: Nemokamoje versijoje trūksta lanksčių ataskaitų.
|                           | 3. Atsakymų šablonai                          |