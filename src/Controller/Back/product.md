/api/products GET ProductController getCollection Liste des produits
/api/products/{id} GET  ProductController getItem Voir un produit précise
/api/products/add GET ProductController Page d’ajout d’un produit
/api/products/add POST ProductController createItem Ajout d’un nouveau produit en BDD
/api/products/{id}/edit GET ProductController Page d’édition d’un produit
/api/products/{id}/edit PATCH ProductController updateItem Edition d’un produit en BDD
/api/products/{id}/delete DELETE ProductController deleteItem Suppression d’un produit