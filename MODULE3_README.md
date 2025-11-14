# Module 3 — Gestion de projets étudiants

Ce document décrit les endpoints, payloads et exemples d'utilisation pour le Module 3 (Projects).

Base URL: / (routes protégées par middleware `auth`)

## Modèles principaux
- Project: id, title, description, supervisor_id, timestamps
- ProjectDeliverable: id, project_id, uploader_id, filename, path, mime_type, size, timestamps
- Pivot project_user: project_id, user_id, role (owner|member)

## Endpoints

Routes (méthode, URI, nom)

- GET    /projects ................................... projects.index
- POST   /projects ................................... projects.store
- GET    /projects/{project} ........................ projects.show
- PUT    /projects/{project} ........................ projects.update
- DELETE /projects/{project} ........................ projects.destroy

- GET    /projects/{project}/members ................. projects.members.index
- POST   /projects/{project}/members ................. projects.members.store
- DELETE /projects/{project}/members/{user} .......... projects.members.destroy

- GET    /projects/{project}/deliverables ............ projects.deliverables.index
- POST   /projects/{project}/deliverables ............ projects.deliverables.store
- GET    /projects/{project}/deliverables/{deliverable} projects.deliverables.download
- DELETE /projects/{project}/deliverables/{deliverable} projects.deliverables.destroy

## Payloads

Create project (POST /projects)

{
  "title": "Projet A",
  "description": "Description...",
  "supervisor_id": 5,        // optional
  "members": [2,3]           // optional array of user ids
}

Add member (POST /projects/{project}/members)

{
  "user_id": 7,
  "role": "member" // optional, default 'member'
}

Upload deliverable (POST /projects/{project}/deliverables)

Form-data with a file field named `file` (pdf, doc, docx, zip, txt). Example cURL:

```bash
curl -X POST -H "Authorization: Bearer <TOKEN>" -F "file=@report.pdf" \
  https://example.test/projects/1/deliverables
```

Download deliverable

GET /projects/{project}/deliverables/{deliverable}

## Permissions (règles principales)
- Création: tout utilisateur authentifié
- Modification / suppression: owner (créateur), supervisor du projet, ou admin
- Visualisation: members, supervisor, admin
- Upload livrable: members, owner, supervisor, admin

## Notes d'implémentation
- Fichiers stockés sur disk `public` sous `projects/{project_id}`. Exécuter `php artisan storage:link`.
- Validation des fichiers: max 10MB, types pdf/doc/docx/zip/txt (configurable).

## Exemples rapides

Créer un projet via cURL (JSON):

```bash
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer <TOKEN>" \
  -d '{"title":"Projet X","description":"..."}' \
  https://example.test/projects
```

Lister les projets (GET /projects) renvoie la collection JSON des projets accessibles.

---

Pour toute question, réponds ici et j'ajusterai la documentation ou les endpoints.
