# Absolunet Cli

This module provides some functionalities through Magento Cli:

## Getting Started

Just run an available module command through Magento cli

### Install tax rates

Install country tax rates from csv file. Default country is CA

Usage

```
boutik:install:tax-rates [country1,countryN] 1
```

Arguments
```
country                 Country ISO ALPHA-2 Code. Comma separated. Ex: ca,us,fr
keep_current_settings   Keep current settings. Ex: 1 or 0. Defaut is 1.
```

### Install tax rules

Install country tax rules from csv file. Default country is CA

Usage

```
boutik:install:tax-rates [country1,countryN] 1
```

Arguments
```
country                 Country ISO ALPHA-2 Code. Comma separated. Ex: ca,us,fr
keep_current_settings   Keep current settings. Ex: 1 or 0. Defaut is 1.
```

### Install default Absolunet Magento settings

Install default catalog, product, seo etc.. settings.
Country settings are set from a csv file.

Usage

```
boutik:install:settings country [project_name] [support_email] [keep_current_settings] [theme_code] 
```

Arguments
```
country                 Default Magento country. Country ISO ALPHA-2 Code
project_name            Project name. Ex: Stokes
support_email           Default support email. Ex: magento@absolunet.com
keep_current_settings   Keep settings already modified in Magento admin. Otherwise all non default settings will be deleted
theme_code              Theme code to enable by default. Ex: Boutik/boutik
```

If you want to set and load your custom settings, create a 'custom.csv' and/or country_code.csv (ex: ca.csv) file(s) under 'app/etc/absolunet/cli/csv/settings' 
folder and then run the command. Your file(s) will be used instead of default config. 

### Create a new store view

Create a new store view according its locale

Usage

```
boutik:create:store-view locale [store_id]
```

Arguments
```
locale                Locale(s) of store view(s) to create. Comma separated. Ex: fr_CA,en_CA,en_US
store_id              Parent Store ID. Default is 1
```

### Import project attributes, attribute sets, attributes options or categories

Import project attributes, attribute sets, attributes options or categories by csv files
If you want to set and import your custom settings, create a [attributs.csv], [attributeset.csv], [attributeOptions.csv], [category.csv]
Example of the files structure here : https://absolunet.jira.com/wiki/spaces/ABSOMAGE/pages/211222599/CLI

Usage

```
boutik:import:attribute_set catalog_product -f attributeset.csv
boutik:import:attribute attributs.csv
boutik:import:attribute_option attributeOptions.csv
boutik:import:category category.csv
```

### Delete test datas

Delete test datas by code type

Usage

```
boutik:delete:test-datas data_code
```

Arguments
```
data_code             Data code(s) to delete  [all|order|quote|customer|shipping|invoice|creditmemo|rma|gift_card|gift_registry|product|category|review]. Comma separated
``` 

### Archiving

Launch orders, invoices, shipments, rma archiving

Usage

```
boutik:archive
``` 

## Contributing

Please contact [Antoine Cinq-Mars](mailto:acinq-mars@absolunet.com) if you want to contribute to this project.

## Versioning

For the versions available, see the [tags on this repository](https://bitbucket.org/absolunet/boutik-cli/downloads/?tab=tags). 

## Authors

* **Aurelien Jourquin**
* **Mathieu Gervais**
* **Cyril Ekoule**

## Release Notes
*   1.10.4
    * Fix products/category deletion
*   1.10.3
    * Fix typo in variable name where {$filename} no longer exist
*   1.10.2
    * Import by cli : Updated argument 'filename' to specific the file path for import.
        * Set the filename without path to target /var/import/csv/filename.csv by default.
        * For attributes, attribute sets, attributes options, categories
    * PHP7.1 + PHP7.2 updated require version in composer.json
*   1.10.1
    * Change CSV separator from ; to ,
*   1.10.0
    * Add cli to import categories
*   1.9.0
    * Add cli to import attributes, attribute sets
*   1.8.1
    * Default Flat Rate Shipping
*   1.8.0
    * Add new command to run archiving
    * Add new default settings
        * Enable Sign Static Files
        * Enable Archiving to 30 days on complete,closed,canceled orders
*   1.7.2
    * Enable Mgt Developer Toolbar by default
*   1.7.1
    * PHP7.1 fix require version
*   1.7.0
    * Add Default SEO settings recommendations
*   1.6.4
    * Add default configuration needed for boutik-promotions
*   1.6.3
    * Add fallback theme to Luma in settings
*   1.6.2
    * Fix Alberta taxe
*   1.6.1
    * Fix di:compile issue
*   1.6.0
    * Add new default settings
        * Timezone to America/NewYork
        * Weight units to kgs
        * Activate admin shared account
    * Fix number of items per page
    * Fix delete customers
    * Add rma sequence table to reset when deleting rma datas
    * You can now use custom files under app/etc/absolunet/cli/csv/settings to import custom settings
    * You can now choose if you want to keep current settings, rates and rules when loading config files. 'keep_current_settings' argument, default is set to Yes.
*   1.5.1
    * XSD schema update
*   1.5.0
    * Read settings in file instead of class property (csv/settings/default.csv)
*   1.4.1
    * Activate Return Path setting by default. Needed for Absolabs and postfix 
*   1.4.0
    * Add a parameter to settings cli to set a theme code
*   1.3.1
    * Composer Autoload fix
*   1.3.0
    * Cleaning sample medias when deleting dummy datas
*   1.2.0
    * Add cli to delete dummy datas
*   1.1.3
    * Refacto to EcgM2 and PSR2 standards 
*   1.1.2
    * README adjustements
*   1.1.1
    * Change store code when updating default names 
*   1.1.0
    * Code cleaning
*   1.0.0
    * Initial release

