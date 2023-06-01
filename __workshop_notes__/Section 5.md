## Section 5

In this section you'll add the ability to style each FAQ block individually.

You're going to add a colour settings panel to the inspector controls so import a `PanelColorSettings` component:

```js
import {
	useBlockProps,
	InspectorControls,
	PanelColorSettings,
} from '@wordpress/block-editor';
```

Then add the component to the `InspectorControls` component:

```js
<PanelColorSettings
	title="Question colours"
	colorSettings={ [
		{
			label: 'Text',
			value: questionTextColor,
			onChange: onChangeQuestionTextColor,
		},
	] }
></PanelColorSettings>
```

Add a `questionTextColor` attribute to `block.json` with a default colour:

```js
"questionTextColor": {
  "type": "string",
  "default": "#ffffff"
}
```

Destructure it from the attributes:

```js
const { category, questionTextColor } = attributes;
```

And add the handler function:

```js
const onChangeQuestionTextColor = ( val ) =>
	setAttributes( { questionTextColor: val } );
```

Create a const called `faqStyles` that will contain the colour values as CSS custom properties (also called CSS variables)

```js
const faqStyles = {
	'--question-text-color': questionTextColor,
};
```

Pass the object to the `useBlockProps` hook on the `div` wrapper element:

```js
<div { ...useBlockProps( { style: faqStyles} ) }>
```

Then add the CSS variable to the `style.css` file:

```css
.wp-block-create-block-wceu-faq-block {
	margin: 12px 0;

	& details {
		background-color: rgb( 199, 226, 228 );
		margin-bottom: 8px;
	}

	& details summary {
		padding: 4px;
		color: var( --question-text-color );
		background-color: rgb( 69, 118, 121 );
	}

	& details .faq-content {
		padding: 4px 16px;
	}
}
```

Now the question text colour changes in the editor, and you can even have separate FAQ blocks with different colours.

Now let's make it work in the front end. To do that we're going to do something similar to what we've just done in `edit.js` but instead do it in `render.php`.

As we've already seen, the attributes are automagically available in `render.php`.

First create an `$faq_styles` variable, and pass it a string with the CSS variable name and the value from the attributes, separated with a colon - i.e. formated as CSS.

```php
$faq_styles = "--question-text-color: " . $attributes["questionTextColor"];
```

Then, similarly to how we passed an object containing the styles to `useBlockProps`, we pass an associative array to `get_block_wrapper_attributes()`. This array has a `style` property which takes the CSS string as it's value.

```php
$show_faqs = '<div ' . get_block_wrapper_attributes( array( "style" =>  $faq_styles  ) ) . '>';
```

Now the colours are showing in the front end too.

Do the same for the question background colour.

Add a new colour setting:

```js
{
  label: 'Background',
  value: questionBackgroundColor,
  onChange: onChangeQuestionBackgroundColor,
}
```

Add a `questionBackgroundColor` attribute to `block.json` with a default colour:

```js
"questionBackgroundColor": {
  "type": "string",
  "default": "#457679"
}
```

Destructure it from the attributes, and add it to `faqStyles`:

```js
const { category, questionTextColor, questionBackgroundColor } = attributes;

const faqStyles = {
	'--question-text-color': questionTextColor,
	'--question-background-color': questionBackgroundColor,
};
```

Add the handler function:

```js
const onChangeQuestionBackgroundColor = ( val ) =>
	setAttributes( { questionBackgroundColor: val } );
```

Add the CSS variable to `style.scss`:

```css
& details summary {
	padding: 4px;
	color: var( --question-text-color );
	background-color: var( --question-background-color );
}
```

Then for the front end we add it to the `$faq_styles` string in `render.php`:

```php
$faq_styles = "--question-text-color: " . $attributes["questionTextColor"];
$faq_styles .= "; --question-background-color: " . $attributes["questionBackgroundColor"];
```

And now we can change both the text colour and the background colour for the questions, and these changes show up in the front end too. Furthermore, separate FAQ blocks can have different colour settings, just as they can have different categories.

Now let's do the same for the answer panel colour settings.

Create the attributes in `block.json`:

```js
"answerTextColor": {
  "type": "string",
  "default": "#000000"
},
"answerBackgroundColor": {
  "type": "string",
  "default": "#c7e2e4"
}
```

Add a new `PanelColorSettings` component called "Answer colours":

```js
<PanelColorSettings
	title="Answer colours"
	colorSettings={ [
		{
			label: 'Text',
			value: answerTextColor,
			onChange: onChangeAnswerTextColor,
		},
		{
			label: 'Background',
			value: answerBackgroundColor,
			onChange: onChangeAnswerBackgroundColor,
		},
	] }
></PanelColorSettings>
```

Create the handler functions for both of these:

```js
const onChangeAnswerTextColor = ( val ) =>
	setAttributes( { answerTextColor: val } );
const onChangeAnswerBackgroundColor = ( val ) =>
	setAttributes( { answerBackgroundColor: val } );
```

Destructure them from the attributes, and add them to `faqStyles`:

```js
const {
	category,
	questionTextColor,
	questionBackgroundColor,
	answerTextColor,
	answerBackgroundColor,
} = attributes;

const faqStyles = {
	'--question-text-color': questionTextColor,
	'--question-background-color': questionBackgroundColor,
	'--answer-text-color': answerTextColor,
	'--answer-background-color': answerBackgroundColor,
};
```

Add the CSS variables to `style.scss`:

```css
& details {
	color: var( --answer-text-color );
	background-color: var( --answer-background-color );
	margin-bottom: 8px;
}
```

And it remains to add them to the `$faq_styles` string in `render.php`:

```php
$faq_styles = "--question-text-color: " . $attributes["questionTextColor"];
$faq_styles .= "; --question-background-color: " . $attributes["questionBackgroundColor"];
$faq_styles .= "; --answer-text-color: " . $attributes["answerTextColor"];
$faq_styles .= "; --answer-background-color: " . $attributes["answerBackgroundColor"];
```

Now each FAQ block can be styled separately, and the styling appears both in the editor and in the front end.
