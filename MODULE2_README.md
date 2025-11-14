Module 2 — Reservations

But: vérification rapide et checklist pour le module "Reservations".

Routes principales
- GET /reservations (index)
- GET /reservations/create (create)
- POST /reservations (store)
- GET /reservations/{reservation} (show)
- PUT /reservations/{reservation} (update - approve/reject)
- GET /calendar (calendar.index)
- GET /reservations/events (reservations.events) — retourne JSON pour FullCalendar

Commandes utiles
- Démarrer : php artisan serve
- Nettoyer caches :
  php artisan view:clear
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  php artisan optimize:clear
- Migrations / Seeders :
  php artisan migrate --seed
  (ou) php artisan migrate:fresh --seed
- Tests :
  php artisan test --verbose
  vendor\bin\phpunit -v

Points techniques
- `Reservation::reservable_type` stocke la classe complète (ex: App\\Models\\Room). C'est intentionnel : le code s'appuie sur morphTo.
- Statuts : constants disponibles dans `App\\Models\\Reservation` (STATUS_PENDING, STATUS_APPROVED, STATUS_REJECTED).
- Conflits : la logique d'overlap est centralisée dans le scope `Reservation::overlapping($start,$end)`.

Checklist pour considérer le module 2 comme terminé
- [ ] Migrations et seeders appliqués et DB de dev peuplée
- [ ] Tous les tests PHPUnit passent
- [ ] Vérification manuelle : création/validation/approbation d'une réservation + calendrier fonctionne
- [ ] Policies pour Room/Equipment/Reservation enregistrées (ok)

Prochaine étape (suggestion)
- Exécuter les tests locaux et me coller la sortie pour corriger les éventuels échecs.
