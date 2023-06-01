## Section 1

In this section you'll scaffold a dynamic block, build it, and register the block in `wceu-faq.php`.

From within the `wp-content/plugins/wceu-workshop` directory, scaffold a new block with:

```bash
npx @wordpress/create-block --no-plugin --variant dynamic wceu-faq-block
```

You will have a new directory called `wceu-faq-block`. This is primarily where you will be working.

Change `block.json` from

```js
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 2,
	"name": "create-block/wceu-faq-block",
	"version": "0.1.0",
	"title": "Wceu Faq Block",
	"category": "widgets",
	"icon": "smiley",
	"description": "Example block scaffolded with Create Block tool.",
	"supports": {
		"html": false
	},
	"textdomain": "wceu-faq-block",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css",
	"render": "file:./render.php"
}
```

to

```js
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 2,
	"name": "create-block/wceu-faq-block",
	"version": "0.1.0",
	"title": "WCEU FAQ",
	"category": "widgets",
	"icon": "list-view",
	"description": "Example block scaffolded with Create Block tool.",
	"supports": {
		"html": false
	},
	"textdomain": "wceu-faq",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css",
	"render": "file:./render.php"
}
```

i.e. change title, icon, and textdomain

Create an empty `package.json` file in the root of the plugin directory, i.e. `wp-content/plugins/wceu-workshop`, and add the following:

```js
{
  "name": "wceu-faq",
  "version": "0.1.0",
  "description": "Block implementation of WCEU FAQ",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1",
    "start": "wp-scripts start --webpack-src-dir=wceu-faq-block",
    "build": "wp-scripts build --webpack-src-dir=wceu-faq-block"
  },
  "dependencies": {
    "@wordpress/scripts": "^25.1.0"
  }
}
```

Register the block in `wceu-faq.php`:

```php
function register_block() {

  register_block_type( __DIR__ . '/build');

}
add_action( 'init', 'register_block' );
```

Save all the changed files, then run `npm install` followed by `npm run build` from the plugin root. You'll now have a `node_modules` folder and a `build` folder. The `build` folder is where the block is actually registered from.

Try out your new block in the editor and in the front end. Add a 'WCEU FAQ' block to your page.
