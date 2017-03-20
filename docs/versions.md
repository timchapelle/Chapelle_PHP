Deux versions sont proposées, afin de bien montrer les différences/ avantages 
entre l'utilisation du framework Angular JS pour le front-end, et l'utilisation unique de PHP :  

1.  **Angular JS**  
    La section Angular JS permet de gérer l'intégralité des opérations 
    (**ajout**/**modification**/ **suppression** de **véhicules**/**réparations**) 
    en restant sur la même page, sans rafraîchissement. 
    Les changements sont visibles en temps réel, et l'enregistrement des données dans 
    la base de données se fait sans contrainte de rechargement pour l'utilisateur. 
    Un feed-back direct est également fourni à l'utilisateur lors de l'envoi de formulaires. 
    _PHP_ ne sert ici qu'à fournir les données issues de la base de données 
    (_JavaScript_ seul ne peut modifier/récupérer des données sur le serveur).  
    Le tout est géré en MVC.  
    J'ai délibérément opté pour une structure en une page, en raison des contraintes 
    techniques demandées pour le projet. 
    Afin de respecter le routage imposé en PHP, je me suis volontairement passé des 
    fonctions de routage d'_Angular JS_, qui permettent de changer le template de la 
    page sans rafraichissement. (cfr [doc officielle - module ngRoute]
    (https://docs.angularjs.org/api/ngRoute)) 
2.  **PHP**  
    La section PHP, au contraire, ne permet pas cette approche dynamique.
    En effet, un rechargement est nécessaire lors de chaque changement dans la 
    base de données, afin de synchroniser l'affichage.

