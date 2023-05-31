## Section 4

In this section you'll make the category user selectable.

Ideally, the default category should be 0, i.e. get all the FAQs.

Remove the default from `block.json`

```js
"attributes": {
  "category": {
    "type": "integer"
  }
}
```

??? But now there's a problem. Instead of showing all the FAQs, the block in the editor doesn't show anything.

We need to use a conditional to add the query object, like we did in `render.php`:

```js
const { category } = attributes;

const query = category ? { 'wceu-faq-cat': category } : {};

const faqs = useEntityRecords( 'postType', 'wceu-faq', query );
```

Now if the category in `block.json` is 0, i.e. the default, all the FAQs show, otherwise it shows the FAQs from the selected category.

We can now move on to making the category user selectable.

We're going to add some Inspector Controls so, as a component can only return one root level element, let's add an empty element as a wrapper:

```js
return (
	<>
		<div { ...useBlockProps() }>
			{ faqs.records &&
				faqs.records.map( ( faq ) => {
					return (
						<details key={ faq.id }>
							<summary>{ faq.title.raw }</summary>
							<section
								class="faq-content"
								dangerouslySetInnerHTML={ {
									__html: faq.content.raw,
								} }
							/>
						</details>
					);
				} ) }
		</div>
	</>
);
```

Import the `InspectorControls` component, and also the `PanelBody` and `SelectControl` components.

```js
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
```

Add these to the JSX

```js
<InspectorControls>
	<PanelBody>
		<SelectControl
			label={ 'Category' }
			onChange={ onChangecCat }
			value={ category }
			options={ [
				{
					label: 'All',
					value: 0,
				},
				{
					label: 'Athens',
					value: 4,
				},
				{
					label: 'Vienna',
					value: 3,
				},
			] }
		/>
	</PanelBody>
</InspectorControls>
```

Here we've hard-coded the IDs, so let's fetch the categories from the database and take a look at what we get back:

```js
const cats = useEntityRecords( 'taxonomy', 'wceu-faq-cat' );
console.log( cats );
```

Create an array that will replace the hard-coded version:

```js
const options =
	cats.records &&
	cats.records.map( ( cat ) => {
		return {
			label: cat.name,
			value: cat.id,
		};
	} );
```

We need to check for `cats.records` as might not yet be populated.

Then we can replace the static options with:

```js
{
	cats.hasResolved && (
		<SelectControl
			label={ 'Category' }
			onChange={ onChangecCat }
			value={ category }
			options={ [
				{
					label: 'All',
					value: 0,
				},
				...options,
			] }
		/>
	);
}
```

Again we need to check for `cats.hasResolved`.

Then we need the `onChangeCat` handler function:

```js
const onChangecCat = ( val ) => setAttributes( { category: Number( val ) } );
```

As the attribute is of `type: integer` we need to cast the value from the SelectControl to a Number.