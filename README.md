# Druptask : automate your Drupal installation

Druptask is a set of command line php utilities which automates some tasks trough [Drush](http://drupal.org/project/drush).
Currently it only deals with Drupal install but other features will be added as the days go by.
Druptask come as a debian and macosx package.

## Installation for debian

```bash
$ dpkg -i druptask.deb
```

## Installation for macosx

```bash
$ druptask.pkg
```

## Usage

These commands will install Drupal where you run them.

Generate the druptask.ini
```bash
$ druptask inifile
```
Fill the druptask.ini generated with your Database parameters, then install your Drupal project named MyDrupalProject
```bash
$ druptask init MyDrupalProject
```
