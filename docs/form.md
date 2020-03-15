Componsant : Form
===

Créer un componsant de gestion de formulaire avec les fonctionnalités suivantes :

* Configurer les différents champs et l'url de soumission du formulaire.
* Lire les données à partir d'un objet source.
* Afficher le formulaire en HTML, avec une valeur initiale pour chaque champ.
* Gérer la soumission du formulaire.
* Modifier un objet avec les valeurs soumises.

:bulb: _Cette page est rédigée comme un guide plutôt qu'un simple énoncé. Vous allez être 
guidé dans la création des différentes classes du composant __Formulaire__. Prêtez attention
à la façon dont __nous abordons chaque classe__ (l'ordre dans lequel leurs méthodes sont 
développées) et prenez soin de bien comprendre les __mécanismes mis en oeuvre__ (classe 
et méthodes abstraites, système d'options, refactorisation, etc). Lorsque vous passerez aux autres composants (__Persistance__, __Routing__ et __Services__),
l'approche et les traitements à développer seront similaires, mais vous ne serez plus guidé._

### Diagramme de classes

![Diagramme de classes](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/form-class-diagram.png)

:warning: Le type &laquo; mixed &raquo; signifie que la donnée peut être de types 
différents : on accepte autant les chaînes de caractères que les nombres, les 
booléens ou les objets. Dans votre code PHP il ne faut pas préciser de type.

Dans ce diagramme, certaines méthodes apparaissent plusieurs fois (exemple : 
__renderWidget()__ pour les classes et interface __AbstractField__, __TextField__, 
__TextareaField__, __CheckboxField__ et __DateTimeField__). Ceci pour vous montrer 
comment les classes vont se spécialiser en redéfinissant ces méthodes. Le détail 
des traitements à réaliser pour chaque méthodes est indiqué plus bas.

### Comment ça marche ?

Une fois le composant terminé et fonctionnel, on pourra créer des formulaires 
comme l'exemple suivant :

```php
use Form\Field;
use Form\Form;

$form = new Form('article');

// Ce premier champ gèrera la propriété 'title' de
// l'instance de la classe Article (que l'on image pour l'exemple)
$form->addField(new Field\TextField('title', 'Titre'));

// Ce second champ gèrera la propriété 'content'
$form->addField(new Field\TextareaField('content', 'Contenu'));

// Etc
$form->addField(new Field\DateTimeField('publishedAt', 'Date de publication', [
    'required' => false,
]));
$form->addField(new Field\CheckboxField('published', 'Publié'));
```

Et utiliser ce formulaire de cette manière :

```php
use App\Entity\Article;

$article = new Article();

// Définir la donné (objet) manipulée par le formulaire
$form->setData($article);

// Lit les données soumises par l'internaute
$form->bindRequest($_POST);

// Vérifie que le formulaire a été soumis
if ($form->isSubmitted()) {
    // Exemple: Enregistrer l'article dans la base de donnée
    // $manager->persist($article);
}
```

On pourra éxecuter un rendu de formulaire&hellip;

```php
echo $form->render();
```

&hellip;ce qui génerera le code HTML suivant :

```html
<form action="" method="post">

    <!-- Champ invisible permettant de déterminer si le formulaire a été soumis -->
    <input type="hidden" name="user" value="user">

    <!-- Champ "Titre" -->
    <div class="form-group">
        <label for="title">Titre</label>
        <input type="text" class="form-control" id="title" name="title" value="" required="required">
    </div>

    <!-- Champ "Contenu" -->
    <div class="form-group">
        <label for="content">Contenu</label>
        <textarea class="form-control" id="content" name="content" required="required"></textarea>
    </div>

    <!-- Champ "Date de publication" -->
    <div class="form-group">
        <label for="publishedAt">Date de publication</label>
        <input type="date" class="form-control" id="publishedAt" name="publishedAt" value="">
    </div>

    <!-- Champ "Publié" -->
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="published" name="published" value="1">
        <label for="published">Publié</label>
    </div>

    <!-- Bouton pour soumettre le formulaire -->
    <button type="submit" class="btn btn-primary">Submit</button>

</form>
```

#### Récupérer les données soumises

L'internaute clique sur le bouton &laquo; Submit &raquo; et le navigateur 
envoie les données saisies au serveur.

Vous pouvez récupérer ces données dans PHP gâce à la variable globale $_POST (si l'attribut _'method'_ de la balise _'form'_ a pour valeur _'post'_).

```php
var_dump($_POST);
// Affiche la valeur saisie dans le champ ayant l'attribut name="title"
echo $_POST['title'];  
```

### Tests unitaires

Vous pouvez lancer la suite de tests automatisés pour vérifier la conformité 
de votre code avec la commande suivante. _(À lancer dans un __GitBash__, ne fonctionnera pas dans un 
terminal windows classique)_.

    vendor/bin/phpunit --testsuite Form

Voir la page [Tests unitaires](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/test.md) 
pour comprendre le résultat de cette commande.


## Détail des classes et méthodes

| Classe | Fichier |
| --- | --- |
| Form\\__FormInterface__ | src/Form/FormInterface.php |
| Form\\__Form__ | src/Form/Form.php |
| Form\Field\\__FieldInterface__ | src/Form/Field/FieldInterface.php |
| Form\Field\\__AbstractField__ | src/Form/Field/AbstractField.php |
| Form\Field\\__TextField__ | src/Form/Field/TextField.php |
| Form\Field\\__TextareaField__ | src/Form/Field/TextareaField.php |
| Form\Field\\__DateTimeField__ | src/Form/Field/DateTimeField.php |
| Form\Field\\__CheckboxField__ | src/Form/Field/CheckboxField.php |


## Classes représentant des champ

Nous allons d'abord développer les classes représentant les champs HTML 
_(classes *Field)_ puis nous développerons la class _Form_ qui exploitera 
ces champs.

### Classe TextField

Commencons par développer la classe __TextField__ sans nous préocupper de la 
classe __AbstractField__ dont elle est censé hériter.

Créez le fichier _src/Form/Field/TextField.php_, et y définir la classe 
__TextField__ qui implémente l'interface __FieldInterface__.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

namespace Form\Field;

class TextField implements FieldInterface
{
    public function getName() : string
    {
        // TODO: Implement getName() method.
    }

    public function getLabel() : string
    {
        // TODO: Implement getLabel() method.
    }

    public function getOptions() : array
    {
        // TODO: Implement getOptions() method.
    }

    public function convertToPhpValue($data)
    {
        // TODO: Implement convertToPhpValue() method.
    }

    public function convertToHtmlValue($data)
    {
        // TODO: Implement convertToHtmlValue() method.
    }

    public function render($data) : string
    {
        // TODO: Implement render() method.
    }
}
```  

</details>

Ajoutez les propriétés privées (__name__, __label__ et __options__) et développez 
le constructeur et les accesseurs (méthodes __get*__) d'après le diagramme.

#### Méthode convertToPhpValue(data)

![Conversion des données](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/form-field-convert-data.png)

Cette méthode doit convertir la donnée du formulaire en donnée PHP. Ici 
ce n'est pas compliqué : que ce soit en HTML (formulaire) ou en PHP, il 
s'agit de texte donc d'une _chaîne de caractère_ (ce sera différent pour 
__CheckboxField__ et __DateTimeField__).

Nous allons nous contenter de supprimer les espaces au début et à la fin 
de la chaîne de caractère soumise par le formulaire à l'aide de la fonction 
[trim](https://www.php.net/manual/fr/function.trim.php). Si la châine de caractères 
résultante est vide, la méthode devra renvoyer la valeur _NULL_. 

À l'aide de votre fichier de test, vérifiez que les espaces sont bien supprimés.

```php
<?php
// test.php

// Initialise le chargement automatique de classes (grâce à composer) 
require __DIR__ . '/vendor/autoload.php';

// Importe la classe que nous développons 
use Form\Field\TextField;

$field = new TextField('title', 'Titre');
echo $field->convertToPhpValue('super test');
echo $field->convertToPhpValue('       super test      ');
```

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

class TextField implements FieldInterface
{
    // ...

    public function convertToPhpValue($data)
    {
        $data = trim($data);

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    // ...
}
```

</details>

#### Méthode convertToHtmlValue(data)

Cette méthode converti la donnée de l'objet en donner à afficher dans le formulaire 
(en vue de la passer à la méthode __render()__). On peut utiliser la fonction 
[trim](https://www.php.net/manual/fr/function.trim.php), mais inutile de contrôler 
une valeur nulle.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

class TextField implements FieldInterface
{
    // ...

    public function convertToHtmlValue($data)
    {
        return trim($data);
    }

    // ...
}
```

</details>

#### Méthode render(data)

Intéressons-nous maintenant à la méthode __render__. Cette méthode doit 
générer le rendu HTML d'un champ de type "text". Exemple :

```html
<div class="form-group">
    <label for="title">Titre</label>
    <input type="text" class="form-control" id="title" name="title" value="" required="required">
</div>
```

##### Rendu initial

Dans votre fichier de test, instanciez votre champ et faites un appel 
à la méthode __render__. Exemple :

```php
<?php
// test.php

// Initialise le chargement automatique de classes (grâce à composer) 
require __DIR__ . '/vendor/autoload.php';

// Importe la classe que nous développons 
use Form\Field\TextField;

$field = new TextField('title', 'Titre');
echo $field->render('Test title');

$field = new TextField('subject', 'Sujet');
echo $field->render('Test subject');
```

À vous d'insérer la valeur de la propriété __name__ dans les attributs 
HTML ```for=""```, ```id=""``` et ```name=""```, la valeur de la propriété 
__label__ comme texte de la balise HTML ```<label>``` et la valeur de 
l'argument __data__ dans l'attribut ```value=""```.

:buld: _Nous allons améliorer cette méthode : la correction vous sera proposée plus bas._

##### Gestion des options

Dans le code HTML du rendu de ce champ, il y a un attribut qui pourrait 
ne pas être toujours présent. Il s'agit de l'attribut ```required="required"``` 
(qui force l'internaute à saisir une valeur). Pour contrôler la présence de 
cette attribut, nous allons à présent __gérer les options__ de ce champs.

Dans le constructeur de votre classe __TextField__, nous allons ajouter des 
options par défaut grâce à la fonction 
[array_replace](https://www.php.net/manual/fr/function.array-replace.php) :

<details>
<summary>:warning: Afficher la correction</summary>

```php
// src/Form/Field/TextField.php
class TextField
{
    // ...

    public function __construct(string $name, string $label = null, array $options = [])
    {
        $this->name = $name;

        // Si l'argument 'label' vaut NULL, on utilise le nom comme libellé
        $this->label = $label ?? $name;

        // On s'assure que ce champ à au moins les options 'required'// 
        // et 'disabled' avec des valeurs par défaut
        $this->options = array_replace([
            'required' => true,
            'disabled' => false,
        ], $options);  
    }

    // ...
}
```   

:bulb: _En savoir plus sur l'[operateur ??](https://www.php.net/manual/fr/language.operators.comparison.php#language.operators.comparison.coalesce)._

</details>

Nous pouvons tester l'effet de 
[array_replace](https://www.php.net/manual/fr/function.array-replace.php)
dans notre fichier de test.

```php
// test.php
require __DIR__ . '/vendor/autoload.php';

use Form\Field\TextField;

$field = new TextField('title', 'Titre', [
    'required'        => false,
    'option-for-test' => 'test',
]);

var_dump($field->getOptions());

// required        : false  // Nous l'avions défini à TRUE par défaut dans le constructeur
// disabled        : false
// option-for-test : test   // Option ajoutée à celle par défaut
``` 

Ainsi nos instances de la classe __TextField__ auront toujours les options 
_required_ et _disabled_ avec des valeurs par défaut. À vous de modifier le 
code de la méthode __render()__ et ajouter ou non les attributs 
```required="required"``` et ```disabled="disabled"``` selon les valeurs de 
```$this->options['required']``` et ```$this->options['disabled']```.

##### Option 'required' et conversion des données

Si le champ est requis, la valeur saisie par l'internaute dans 
le formualire ne doit pas être vide (chaîne de caractères vide ou NULL).

Ajoutez ce comportement à la méthode __convertToPhpValue()__ en 
&laquo; levant &raquo; une exception __InvalidArgumentException__ le cas échant.

:buld: _On &laquo; lève &raquo; une exception avec le code suivant :_
 
```php
use InvalidArgumentException;

// ...

throw new InvalidArgumentException("Message décrivant l'erreur");
``` 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

namespace Form\Field;

use InvalidArgumentException;

class TextField implements FieldInterface
{
    // ...

    public function convertToPhpValue($data)
    {
        $data = trim($data);

        if (empty($data)) {
            if ($this->options['required']) {
                throw new InvalidArgumentException("The field '$this->name' is required.");
            }

            return null;
        }

        return $data;
    }

    // ...
}
```

</details>

##### Analyse des rendus des différents champs

Comparons les rendus des différents champs.

Rendu de __TextField__ (et __DateTimeField__ en changeant l'attribut 
```type="date"```)

```html
<!-- Group -->
<div class="form-group">
    <!-- Label -->
    <label for="title">Titre</label>
    <!-- Widget -->
    <input type="text" class="form-control" id="title" name="title" value="" required="required">
</div>
```

Rendu de __TextareaField__

```html
<!-- Group -->
<div class="form-group">
    <!-- Label -->
    <label for="content">Contenu</label>
    <!-- Widget -->
    <textarea class="form-control" id="content" name="content" required="required"></textarea>
</div>
```

Rendu de __CheckboxField__

```html
<!-- Group -->
<div class="form-group form-check">
    <!-- Widget -->
    <input type="checkbox" class="form-check-input" id="published" name="published" value="1">
    <!-- Label -->
    <label for="published">Publié</label>
</div>
```

Observations :
* On retrouve un groupe (```<div>```), un libellé (```<label>```) et 
un widget  (```<input>```).
* L'ordre peut changer entre le libellé et le widget (dans le cas 
de __CheckboxField__).
* Le widget est différent pour chaque champ (```<textarea>``` ou ```<input>``` avec 
des valeurs différentes pour l'attribut ```type=""```).

Pour refléter ces _variations de rendu_, nous allons modifier la méthode 
__render()__ et créer des méthode __renderLabel()__ et __renderWidget()__. 
Le but est de préparer la classe __AbstractField__ qui permettra aux autre 
classes __*Field__ de se spécialiser. 

#### Méthode renderLabel() 

Bien qu'il soit positionné différement selon les champs, le rendu du libellé 
est toujours le même. Développez la méthode __renderLabel()__ qui ne renvoi 
que la balise ```<label ...> ... </label>```. 

#### Méthode renderWidget(data) 

Développez la méthode __renderWidget()__ qui ne renvoi que la balise ```<input ...>```.

#### Méthode render(data)

Nous pouvons maintenant modifier la méthode __render()__. Celle-ci va effectuer 
le rendu du _groupe_ ```<div ...> ... </div>``` et appeler les méthodes
__renderLabel()__ et __renderWidget()__ pour insérer le _libellé_ et le _widget_
aux bons endroits.

### Class AbstractField

Maintenant que notre classe __TextField__ est fonctionnelle, nous allons déplacer 
du code dans la classe __AbstractField__. Le but est de regrouper les comportements
communs à tous les champs (classes __*Field__) pour leurs permettre via l'héritage
de se spécialiser.

Créer la classe __AbstractField__ (qui implémente l'interface __FieldInterface__) 
et y déplacer toutes les propriétés et méthodes de la classe __TextField__, 
_sauf_ la méthode __renderWidget()__. Dans la classe __AbstractField__, on ajoute 
une méthode _abstraite_ et _protégée_ __renderWidget()__ pour forcer les classes filles à (re)définir
cette méthode.

:bulb: _Une classe ou une méthode abstraite se déclare en ajoutant le mot clé ```abstract``` 
au début de sa définition._

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/AbstractField.php

namespace Form\Field;

abstract class AbstractField implements FieldInterface
{
    protected $name;

    protected $label;

    protected $options;

    public function __construct(string $name, string $label = null, array $options = [])
    {
        $this->name = $name;
        $this->label = $label ?? $name;
        $this->options = array_replace([
            'required' => true,
            'disabled' => false,
        ], $options);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function convertToPhpValue($data)
    {
        $data = trim($data);

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function convertToHtmlValue($data)
    {
        return trim($data);
    }

    public function render($data): string
    {
        return
            '<div class="form-group">' .
                $this->renderLabel() .
                $this->renderWidget($data) .
            '</div>';
    }

    protected function renderLabel(): string
    {
        return '<label for="' . $this->name . '">' . $this->label . '</label>';
    }

    abstract protected function renderWidget($data): string;
}
```

</details>

:bulb: _Le fait de réorganiser la méthode __render__ en plusieurs méthodes 
(__renderLabel__ et __renderWidget__) s'appelle la refactoration ou 
([réusinage de code](https://fr.wikipedia.org/wiki/R%C3%A9usinage_de_code)).
Chacune de ces méthodes a maintenant un rôle bien précis, et les classes 
filles vont pouvoir spécialiser ces méthodes en les redéfinissant._

#### Retour à la classe TextField

La classe __TextField__ hérite maintenant de la classe __AbstractField__ et 
(re)définit la méthode __renderWidget()__.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

namespace Form\Field;

class TextField extends AbstractField
{
    protected function renderWidget($data): string
    {
        return 
            '<input type="text" class="form-control"' . 
                ' id="' . $this->name . '"' . 
                ' name="' . $this->name . '"' . 
                ' value="' . $data . '"' . 
                ($this->options['required'] ? ' required="required"' : '') . 
            '>';
    }
}
```

</details>

Nous avons donc simplifié notre classe __TextField__ en déplacant la logique 
commune à tous les champs dans la classe mère __AbstractField__.

#### Amélioration 1 : rendu des attributs

La méthode __renderWidget()__ consiste en de multiples concaténations de 
chaînes de caractères et n'est pas très lisible.

Si l'on analyse le rendu d'une balise HTML, on remarque que ses attributs sont 
une répétition du modèle ```key="value"```, ce qui nous rappelle la structure 
d'un tableau en PHP.

L'idéal serait donc de pouvoir convertir un tableau PHP en attributs de balise HTML.

Partant du tableau PHP suivant :

```php
$attributes = [
    'type'  => 'text',
    'class' => 'form-control',
    'id'    => 'title',
];
```

On souhaite obtenir la chaîne de caractères suivante :

``` type="text" class="form-control" id="title"```

Dans votre fichier de test, développez un algorithme réalisant cette conversion.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// test.php

$attributes = [
    'type'     => 'text',
    'class'    => 'form-control',
    'id'       => 'title',
    'name'     => 'title',
    'value'    => 'test',
    'required' => 'required',
    'disabled' => 'disabled',
];

$output = '';
foreach ($attributes as $key => $value) {
    // Attention à l'espace au début de la chaîne de caractères
    $output .= " $key=\"$value\"";
}

echo $output;
// Affiche
// type="text" class="form-control" id="title" name="title" value="test" required="required" disabled="disabled"

```

</details>

Maintenant que vous avez développé l'algorithme, déplacez-le dans une 
méthode _protégée_ __renderAttributes(array $attributes)__ de la 
classe __AbstractField__. Profitez-en pour ajouter à l'argument 
__$attributes__ les options __required__ et __disabled__ (si toutefois
leur valeur vaut _TRUE_).

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/AbstractField.php

namespace Form\Field;

abstract class AbstractField implements FieldInterface
{
    // ...

    protected function renderAttributes(array $attributes): string
    {
        if ($this->options['required']) {
            $attributes['required'] = 'required';
        }
        if ($this->options['disabled']) {
            $attributes['disabled'] = 'disabled';
        }

        $output = '';
        foreach ($attributes as $key => $value) {
            $output .= " $key=\"$value\"";
        }

        return $output;
    }

    // ...
}
```

</details>

Vous pouvez maintenant utiliser cette méthode pour améliorer le code de la
méthod __TextField::renderWidget__. 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextField.php

namespace Form\Field;

class TextField extends AbstractField
{
    protected function renderWidget($data): string
    {
        $attributes = [
            'type'        => 'text',
            'class'       => 'form-control',
            'id'          => $this->name,
            'name'        => $this->name,
            'value'       => $data,
        ];

        return sprintf('<input%s>', $this->renderAttributes($attributes));
    }
}
```

</details>

:buld: _Le fait d'avoir créé une méthode __renderAttributes()__ pour isoler le rendu des 
attributs d'une balise constitue une nouvelle __refactorisation__ de notre code._ 

### Classe TexareaField

La classe __TextareaField__ fonctionne quasiment comme la classe __TextField__.

Créez la classe __TextareaField__ héritant de la classe __TextField__ et redéfinissez la 
méthode __renderWidget()__ pour obtenir un rendu de balise 
```<textarea>...</textarea>``` plutôt qu'une balide ```<input>```.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextareaField.php

namespace Form\Field;

class TextareaField extends TextField
{
    protected function renderWidget($data): string
    {
        $attributes = [
            'class' => 'form-control',
            'id'    => $this->name,
            'name'  => $this->name,
        ];

        return sprintf('<textarea%s>%s</textarea>', $this->renderAttributes($attributes), $data);
    }
}
```

</details>

### Classe CheckboxField

#### Amélioration 2 : résolution des options   

Dans le constructeur de notre class __AbstractField__, nous avons utilisé la 
fonction [array_replace](https://www.php.net/manual/fr/function.array-replace.php)
pour définir des options par défaut. Mais ces options par défaut pourraient
être différentes selon le champ. Par exemple nous pourrions décider qu'un champ 
de type _text_ est requis par défaut, mais qu'un champ de type _checkbox_ ne l'est pas
(l'internaute n'est pas obligé de cocher la case).
 
Nous allons donc _refactoriser_ la classe __AbstractField__ pour chaque classe fille puisse
spécialiser ses options par défaut.

Dans la class __AbstractField__, ajoutez une méthode _protégée_ __resolveOptions(array $options)__.
Cette méthode utilisera la fonction array_replace comme dans le constructeur. Ajoutez un appel à 
cette méthode dans le constructeur pour initialiser la propriété __options__ comme auparavant 
(on conserve le comportement actuel en déplacant du code). 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/TextareaField.php

namespace Form\Field;

abstract class AbstractField implements FieldInterface
{
    // ...

    public function __construct(string $name, string $label = null, array $options = [])
    {
        $this->name = $name;
        $this->label = $label ?? $name;
        $this->options = $this->resolveOptions($options);
    } 

    // ...
   
    protected function resolveOptions(array $options): array
    {
        return array_replace([
            'required' => true,
            'disabled' => false,
        ], $options);
    }

    // ...
}
```

</details>

Vous pouvez maintenant créer le classe __CheckboxField__.

Un champ HTML de type ```input="checkbox"``` est un peu particulier :
* Si la case est cochée, il renvoi la valeur de l'attribut ```value="1"```
(ici &laquo; 1 &raquo;).
* Si la case n'est pas cochée, il ne renvoi rien du tout et n'est même 
pas présent dans la variable PHP globale __$_POST__. 

Vérifiez ce comportement dans votre fichier de test avec le code suivant :

```php
<?php
// Test .php
var_dump($_POST);
?>
<form method="post">
    <input type="checkbox" name="test" value="1">
    <button type="submit">Submit</button>
</form>
```

La méthode __convertToPhpValue()__ devra :
* Convertir l'argument __data__ en booléen.
* Si __data__ vaut FALSE et que le champ est requis : lever une exception 
__InvalidArgumentException__. 
* Sinon, renvoyer ce booléen.

La méthode __convertToHtmlValue()__ se contentera de convertir l'argment 
__data__ en booléen.

La méthode __resolveOptions()__ définira les options par défaut : 
 * _required_ à FALSE 
 * _disabled_ à FALSE 

La méthode __renderWidget__ ajouter définir l'attribut ```value="1"``` et 
ajouter l'attribut ```checked="checked"``` si $data vaut TRUE.

La méthode __render()__ devra inverser les positions du libellé (```<Label>...```) 
et du widget (```<input ...>```).

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/CheckboxField.php

namespace Form\Field;

use InvalidArgumentException;

class CheckboxField extends AbstractField
{
    public function convertToPhpValue($data)
    {
        $data = (bool)$data;

        if (!$data && $this->options['required']) {
            throw new InvalidArgumentException("The field '$this->name' is required.");
        }

        return $data;
    }

    public function convertToHtmlValue($data)
    {
        return (bool)$data;
    }

    public function render($data): string
    {
        return
            '<div class="form-group form-check">' .
                $this->renderWidget($data) .
                $this->renderLabel() .
            '</div>';
    }

    protected function renderWidget($data): string
    {
        $attributes = [
            'type'  => 'checkbox',
            'class' => 'form-check-input',
            'id'    => $this->name,
            'name'  => $this->name,
            'value' => 1,
        ];

        if ($data) {
            $attributes['checked'] = 'checked';
        }

        return sprintf(
            '<input%s>',
            $this->renderAttributes($attributes)
        );
    }

    protected function resolveOptions(array $options): array
    {
        return array_replace([
            'required' => false,
            'disabled' => false,
        ], $options);
    }
}

```

</details>

### Classe DateTimeField

Créez la classe __DateTimeField__.

:bulb: _[Voir la page Tips](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/tips.md) 
pour manipuler la classe __DateTime__._

La méthode __convertToPhpValue()__ doit :
* supprimer les espaces autour de la chaîne de caractères saisie par l'internaute
(fonction trim).
* Si la chaîne de caractères résultante est vide:
    * Lever une exception si le champ est requis.
    * Sinon renvoyer la valeur NULL.
* Sinon (chaîne de caractère non vide) convertir la chaîne de caractères 
 en instance de la classe __DateTime__.

La méthode __convertToHtmlValue__() doit :

* Si l'argument __date__ est un instance de la classe DateTime 
(utiliser l'opérateur operateur [instanceof](https://www.php.net/manual/fr/language.operators.type.php)),
convertir la date en chaîne de caractères (format 'Y-m-d').
* Sinon renvoyer la valeur NULL.

La méthode __renderWidget()__ aura la particularité de définir l'attribut ```type="date"```. 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Field/DateTimeField.php

namespace Form\Field;

use DateTime;
use Exception;
use InvalidArgumentException;

class DateTimeField extends AbstractField
{
    public function convertToPhpValue($data)
    {
        $data = trim($data);

        if (empty($data)) {
            if ($this->options['required']) {
                throw new InvalidArgumentException("The field '$this->name' is required.");
            }

            return null;
        }

        try {
            $transformed = new DateTime($data);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Failed to transform the request data into a date time object.");
        }

        return $transformed;
    }

    public function convertToHtmlValue($data)
    {
        if ($data instanceof DateTime) {
            return $data->format('Y-m-d');
        }

        return null;
    }

    protected function renderWidget($data): string
    {
        $attributes = [
            'type'        => 'date',
            'class'       => 'form-control',
            'id'          => $this->name,
            'name'        => $this->name,
            'placeholder' => $this->label,
            'value'       => $data,
        ];

        return sprintf('<input%s>', $this->renderAttributes($attributes));
    }
}

```

</details>

:thumbsup: Vous avez fini de développer les classes représentant les champs HTML ! :clap: 

## La classe Form

Préparez votre fichier de test pour notre classe __Form__.

```php
// test.php
require __DIR__ . '/vendor/autoload.php';

use Form\Form;

$form = new Form('test');
```

Créez la classe __Form__ qui implémente l'interface __FormInterface__. 
* Ajoutez les propriétés privées.
* Ajoutez le __constructeur__, qui devra :
    * Initialiser les propriétés __name__ et __action__ avec la valeurs des arguments (paramètres).
    * Initialiser la propriété __fields__ avec un _tableau vide_.
    * Initialiser la propriété __submitted__ avec la valeur _FALSE_.
* Développez les mutateurs __setAction()__ et __setData()__. Et l'accesseur 
__isSubmitted()__ (qui renvoie la valeur de la propriété __submitted__).

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

use Form\Field\FieldInterface;

class Form implements FormInterface
{
    private $name;

    private $action;

    private $data;

    private $fields;

    private $submitted;

    private $accessor;


    public function __construct(string $name, string $action = null)
    {
        $this->name = $name;
        $this->action = $action;

        $this->fields = [];
        $this->submitted = false;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function setData(object $data): void
    {
        $this->data = $data;
    }

    public function addField(FieldInterface $field): void
    {

    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function bindRequest(array $request): void
    {

    }

    public function render(): string
    {

    }
}

```

</details>

#### Méthode addField()

La méthode __addField()__ permet d'ajouter des champs à notre formulaire. 
Son paramètre __field__ accepte n'importe quel objet dont la classe implémente __FieldInterface__.

Développez la méthode __addField__ qui ajoutera l'objet de l'argument 
__field__ au tableau de la propriété __fields__.

Cette méthode devra lever un exception InvalidArgumentException si l'on tente d'ajouter un champ
ayant le même nom (_FieldInterface::getName()_) qu'un autre champ déjà ajouté.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// ...
use InvalidArgumentException;

class Form implements FormInterface
{
    // ...

    public function addField(FieldInterface $field): void
    {
        $name = $field->getName();

        if (array_key_exists($name, $this->fields)) {
            throw new InvalidArgumentException("Field '$name' is already defined.");
        }

        $this->fields[$name] = $field;
    }

    // ...
}

```

</details>

#### PropertyAccess

Avant d'aborder le développement des méthodes __render()__, __bindRequest()__
et __isSubmitted()__, veuillez vous familiariser avec le composant PropertyAccess 
[Voir page Tips](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/tips.md).

#### Méthode render()

Le but est de générer le code HTML du formulaire, en remplacant ```{form.action}``` 
et ```{form.name}``` par les valeurs des propriétés et en insérant le rendu champ du formulaire.

```html
<form action="{form.action}" method="post">
    <!-- Champ invisible permettant de déterminer si le formulaire a été soumis -->
    <input type="hidden" name="{form.name}" value="{form.name}">
    
    <!-- Rendu des champs du formulaire -->

    <!-- Bouton pour soumettre le formulaire -->
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// ...

class Form implements FormInterface
{
    // ...

    public function render(): string
    {
        $output =
            '<form action="' . $this->action . '" method="post">' .
                '<input type="hidden" name="' . $this->name . '" value="' . $this->name . '">';

        foreach ($this->fields as $name => $field) {
            $output .= $field->render(null);
        }

        $output .=
                '<button type="submit" class="btn btn-primary">Submit</button>' .
            '</form>';

        return $output;
    }

    // ...
}

```

</details>

Comme vous pouvez le constater, on passe la valeur NULL à la méthode 
FieldInterface::render() pour ne pas rencontrer d'erreur. Nous allons 
maintenant voir comment récupérer cette valeur dans l'objet manipulé 
par le formulaire. 

Dans votre fichier de test, créer une instance de la classe __User__, 
définir la donnée manipulée par le formulaire (grâce à la méthode 
__setData()__) et ajouter un champ &laquo; _Email_ &raquo; 
(pour gérer le propriété __email__ de l'objet __$user__).

```php
// test.php
require __DIR__ . '/vendor/autoload.php';

use App\Entity\User;
use Form\Form;
use Form\Field\TextField;

$user = new User();

$form = new Form('test');
$form->addField(new TextField('email', 'Adresse email'));
$form->setData($user);

echo $form->render();
```

La méthode __FieldInterface::getName()__ renvoie le nom de 
la propriété ciblée dans la classe __User__. 

Dans la méthode __render()__ de la classe __Form__, _pour chaque champ_, 
utilisez le componsant __PropertyAccess__ pour récupérer la valeur à 
partir de l'objet stocké dans la propriété __data__ du formulaire. 
Utilise le champ pour convertir cette donnée en HTML (méthode __convertToHtmlValue()__). 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// Attention à importer la classe
use Symfony\Component\PropertyAccess\PropertyAccess;

class Form implements FormInterface
{
    // ...

    public function render(): string
    {
        // Créer une instance de PropertyAccess
        $accessor = PropertyAccess::createPropertyAccessor();

        $output =
            '<form action="' . $this->action . '" method="post">' .
                '<input type="hidden" name="' . $this->name . '" value="' . $this->name . '">';

        foreach ($this->fields as $name => $field) {
            // Récupère la valeur de la propriété $name dans l'objet $this->data
            $data = $accessor->getValue($this->data, $name);
            
            // Utilise le champ pour convertir cette donnée (PHP) en HTML
            $value = $field->convertToHtmlValue($data);
    
            // Donne cette valeur à la méthode render() du champ
            $output .= $field->render($value);
        }

        $output .=
                '<button type="submit" class="btn btn-primary">Submit</button>' .
            '</form>';

        return $output;
    }

    // ...
}

```

</details>

#### Modification du constructeur

La classe Form a une propriété __accessor__. Dans la méthode __render()__ vous avez
créé une instance de __PropertyAccessor__. Cette instance nous sera aussi utile dans 
la méthode __bindRequest()__. Déplacez la création de l'instance de __PropertyAccessor__
dans le constructeur, et utilisez la propriété accessor dans la méthode __render()__. 

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// ...
// Attention à importer la classe
use Symfony\Component\PropertyAccess\PropertyAccess;

class Form implements FormInterface
{
    // ...

    private $accessor;


    public function __construct(string $name, string $action = null)
    {
        // ...

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    // ...

    public function render(): string
    {
        // ...
        foreach ($this->fields as $name => $field) {
            $data = $this->accessor->getValue($this->data, $name);
            
            // ...
        }

        // ...
    }

    // ...
}

```

</details>

#### Méthode assertConfigured()

La méthode render() fonctionne, mais il y a une dernière chose à gérer : 
on ne devrait pas pouvoir appeler cette méthode si :
* la donnée à manipuler du formulaire n'est pas définie (la propriété
__data__ est vide). 
* aucun champ n'a été ajouté (la propriété __fields__ est un tableau vide).

Nous allons donc ajouter une créer une méthode _privée_ __assertConfigured()__
qui lève une exception (classe __LogicException__) dans ces 2 cas.

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// ...
use LogicException;

class Form implements FormInterface
{
    // ...

    private function assertConfigured()
    {
        if (null === $this->data) {
            throw new LogicException("Call Form::setData() first.");
        }
        if (empty($this->fields)) {
            throw new LogicException("Call Form::addField() first.");
        }
    }

    // ...
}

```

</details>

Une fois cette méthode fonctionelle (vérifier dans votre fichier test.php :wink:), 
ajouter un appel à cette méthode au début de la méthode __render()__. On ne 
pourra donc plus appeler __render() sans avoir ajouté de champ(s) et défini la 
l'objet à manipuler. 

#### Méthode bindRequest()

Cette méthode reçoit en paramètre les données soumises (variable globale $_POST) et doit :
* Vérifier que le formulaire est configuré en appelant la méthode __assertConfigured()__.
* Vérifier qu'un index du même nom que le champ de type hidden est présent dans la tableau 
associatif des données soumises.
    * Si oui, définir la propriété submitted à vrai.
    * Sinon, s'arrête ici (avec l'instruction _return_).
* Pour chaque champ (instances de __FieldInterface__) : 
    * Vérifier qu'un index du même nom (méthode __getName__) que ce champ est présent dans 
    la tableau associatif des données soumises.
        * Si oui, convertir la donnée en appelant la méthode __convertToPhpValue()__.
        * (sinon, champ suivant)

:bulb: _Utilisez la fonction [array_key_exists](https://www.php.net/manual/fr/function.array-key-exists.php) !_

<details>
<summary>:warning: Afficher la correction</summary>

```php
<?php
// src/Form/Form.php

namespace Form;

// ...

class Form implements FormInterface
{
    // ...

    public function bindRequest(array $request): void
    {
        $this->assertConfigured();

        if (array_key_exists($this->name, $request) && $request[$this->name] === $this->name) {
            $this->submitted = true;
        } else {
            return;
        }

        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $request)) {
                $value = $field->convertToPhpValue($request[$name]);

                $this->accessor->setValue($this->data, $name, $value);
            }
        }
    }

    // ...
}

```

</details>

Pour vous assurrer que la classe Form est fonctionnelle, complètez votre fichier de test :
* Ajoutez d'autres champs (en appelant la méthode __addField()__).
* Appelez la méthode __bindRequest($_POST)__ pour lire les données soumises.
* Utilisez la méthode __isSubmitted()__ pour déterminer si le formulaire a été soumis.
    * Si oui, faites un var_dump($user) pour vérifier si votre instance de la classe User
    a été modifiée par le formulaire.
      
Testez entièrement votre formulaire !

<details>
<summary>:warning: Afficher la correction</summary>

```php
// test.php
require __DIR__ . '/vendor/autoload.php';

use App\Entity\User;
use Form\Form;
use Form\Field\TextField;
use Form\Field\DateTimeField;
use Form\Field\CheckboxField;

$user = new User();

$form = new Form('test');

$form->addField(new TextField('email', 'Email'));
$form->addField(new TextField('name', 'Nom'));
$form->addField(new DateTimeField('birthday', 'Date de naissance', [
    'required' => false,
]));
$form->addField(new CheckboxField('active', 'Actif'));


$form->setData($user);

// Le formulaire lit les données soumises
$form->bindRequest($_POST);

// Si le formulaire a été soumis
if ($form->isSubmitted()) {
    // On vérifie si notre instance de la classe User
    // a bien été modifiée par le formulaire
    var_dump($user);
}

// Affiche un rendu du formulaire
echo $form->render();
```

</details>

:thumbsup: Vous avez fini de développer le composant Form !!! :clap: 

Il est temps d'utiliser ce nouveau composant dans notre application !

Modifiez les fichiers :
* intro/create.php
* intro/update.php
* intro/delete.php

Vous trouverez une correction complète de ce composant et des modifications des 
fichiers de la partie _Intro_ dans la branche 
[form](https://github.com/ekyna-learn/php-oo-framework/tree/form)
du dépôt original.

Un autre composant à développer vous attend : passez à la partie 
[Persistance](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/persistence.md).
