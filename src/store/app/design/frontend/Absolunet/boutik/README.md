# Absolunet Boutik Thème

Ce thème est un boilerplate basé sur le thème Blank de Magento 2 en SCSS

## Contributing

Please contact [Atelier](mailto:atelier@absolunet.com) if you want to contribute to this project.

## Versioning

For the versions available, see the [tags on this repository](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/downloads/?tab=tags).

## Compatibility

* Magento 2.2: 1.3.5 or later
* Magento 2.1 & 2.0: 1.3.4 or older

## Authors

* **Benoit Renaud**
* **Sébastien Morin-Caron**
* **Jean-Baptiste Landry**
* **Melissa Boutry**
* **Matthieu Richard**

## Dependencies
1. [absolabs](https://bitbucket.org/absolunet/absolabs)
1. [nwayo](https://github.com/absolunet/nwayo/)
1. [magento2-theme-blank-sass](https://github.com/SnowdogApps/magento2-theme-blank-sass)

## Release Notes
*   2.0.1
    * Bonification affichage des filtres en mobile
*   2.0.0
    * Refactoring complet du css avec nwayo et ajustements visuels
*   1.5.1
    * Fix reflow foundation for off-canvas and equalizer
    * Fix bug horizontal scroll with widget full-width
*   1.5.0
    * **Modifications principalement fait dans la page catalogue :**
        * Intégration du module Amasty Improved Layered Navigation
        * Voir prix régulier et prix spécial.
        * Si plusieurs prix (Configurable), mettre: À partir de...
        * Avertir si le niveau de stock est inferieur a un certain nombre (paramétrable en admin)
        * Afficher le manufacturer
        * Afficher le % d'économie avec le prix spécial
        * Afficher le tier price
        * En mobile, remplacer les swatchs par le nombre d'options primaires disponibles (ex: nmb. de couleurs dispo)
        * Afficher les ratings
        * Afficher le nombre de reviews
        * Pouvoir ajouter à la wishlist
*   1.4.5
    * Ajustements ergonomique du panier d'achat
*   1.4.4
    * Hotfix - Problème avec la recherche dans l'entete
*   1.4.3
    * Mise à jour de foundation 6.4.3
*   1.4.2
    * Fichier manquant de en_US.csv pour les traductions de boutik
*   1.4.1
    * Refacto package PROJECT_NAME
    * Mise à jour de la version de kafe
    * Corrections des issues : [#13](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/issues/13/checkout-page-freeze) et [#14](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/issues/14/mont-e-version-220-menu-accord-on-de)
*   1.4.0
    * Pleinement compatible Magento 2.2 (SCSS update)
    * Corrections des issues : [#12](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/issues/12/menu-my-account-quand-on-est-logu-il-nest), [#16](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/issues/16/le-compteur-de-lic-ne-du-cart-est-mal) et [#20](https://bitbucket.org/absolunet/boutik-theme-frontend-boutik/issues/20/textfield-affiche-sous-les-options-dun)
*   1.3.9
    * Compatibilité Magento 2.2
*   1.3.8
    * PHP7.1 fix require version
*   1.3.7
    * Update nwayo 3.3.5
*   1.3.6
    * Correction dans nwayo pour pouvoir binder des actions sur le jQuery de Magento
*   1.3.5
    * Compatibilité Magento 2.2RC
*   1.3.4
    * Correction du jQueryGlobal qui ne fonctionnait plus
*   1.3.3
    * Suppression des dépendences trop restrictives dans le composer.json
*   1.3.0
    * Update nwayo 3.3.4
*   1.2.0
	* Ajout du token PROJECT_NAME dans le nwayo-root
    * Ajout des composantes absolabs
    * Remove du layout update qui est déjà dans le module de boutik-menu
*   1.1.0
    * Add feature customer login, create and base of dashboard
*   1.0.0
    * Initial release
