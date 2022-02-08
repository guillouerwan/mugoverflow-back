## La liste des endpoints API :

### Login

POST : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/login_check

```json
{
	"username": "user@user.com",
	"password": "user"
}
```

### Profil

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/profil

### Update profil

PUT : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/profil/update

```json
{
	"email": "nico@nico1.com",
	"firstname": "Nicolas",
	"lastname": "Lenne",
	"status": 3,
	"promo": 2
}
```

L’update du password se fait à part, sur un autre endpoint (voir plus bas)

### Update / ajout photo de profil :

POST : http://localhost:8000/api/profil/image

Le champ d’upload doit être “imageFile”

Note de jc sur autre issue (https://github.com/O-clock-Xandar/Projects/issues/40) :
Créer un form classique avec attribut enctype="multipart/form-data", via https://developer.mozilla.org/fr/docs/Web/API/FormData/Using_FormData_Objects

Remplacer le code XMLHttpRequest() par une requête axios !

Faites bien gaffe à la syntaxe fileInputElement.files[0] qui contient le fichier à envoyer.

### Enregistrement

POST : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/register

```json
{
	"email": "nico@nico.com",
	"firstname": "Nicolas",
	"lastname": "Lenne",
	"password": "ABCabc1!",
	"checkPassword": "ABCabc1!",
	"status": 1,
	"promo": 2
}
```

La promo faire un select en récupérant les données de promo via le endpoint promo (voir plus bas)
Le status idem faire un select en récupérant les données de status via le endpoint status (voir plus bas)
Mot de pass à 8 caractère minimum dont 1 chiffre, une majuscule et un caractère spécial

### Changement password

PUT : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/profil/password

```json
{
  "currentPassword": "ABCabc1!",
  "newPassword": "ABCabc2!",
  "checkPassword": "ABCabc2!"
}
```

Mot de pass à 8 caractère minimum dont 1 chiffre, une majuscule et un caractère spécial

### Suppression de son compte

POST : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/profil/delete
{
  "currentPassword": "ABCabc1!",
}

### Liste des promos :

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/promos

### Liste des status (staff ou étudiant…) :

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/status

### Liste produits

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products

### Liste 10 produits random

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?type=random

### Liste 3 produits random (pour fake favorite user product)

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?type=favoriteProduct

### Liste 10 produits de promo

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?type=favoritePromo

### Recherche produit :

http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?type=favoritePromo

### Liste 10 derniers produits

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?search=produit_a_rechercher

### Un produit

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products/Nom-du-produit

### Rechercher un produit avec un mot clé

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/products?search=produit_a_rechercher

### Les catégories 

GET : http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/categories

### Untégories (+ produits)

GET : 
http://nicolaslenne-server.eddi.cloud/projet-Mug-Overflow-back/public/api/categories/Nom-categorie/products

