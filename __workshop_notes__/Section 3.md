## Section 3

In this section you'll render the list of FAQs in the editor.

Open `edit.js`, and import `useEntityRecords`:

```js
import { useEntityRecords } from '@wordpress/core-data';
```

`useEntityRecords` is a custom React hook that enables you to fetch data from the WordPress database.

Replace the `Edit()` function with this:

```js
export default function Edit() {

  const faqs = useEntityRecords( 'postType', 'wceu-faq' );

	return (
		<div { ...useBlockProps() }>
			{
        faqs.records && faqs.records.map( (faq) => {
          return (
            <details key={ faq.id }>
              <summary>{ faq.title.raw }</summary>
              <section dangerouslySetInnerHTML={{ __html: faq.content.raw }} />
            </details>
          )
        })  
      }
		</div>
	);
}
```

Now you can see all the FAQs in the editor too

Now add a query parameter to `useEntityRecords` to fetch just the FAQs from a certain category:

```js
 const faqs = useEntityRecords( 'postType', 'wceu-faq', { 'wceu-faq-cat': 4 } );
 ```

 We have to pass the ID of the custom taxonomy term here, we can't use the slug.

 So it makes sense to also use the ID in `render.php` too, as ultimately that is what we'll need to store in the attributes.

 Change the `tax_query` args to:

 ```php
 $args[ 'tax_query' ] = array(
  array(
    'taxonomy' => 'wceu-faq-cat',
    'field'    => 'term_id',
    'terms'    => $attributes[ 'category' ]
  )
);
```

And change the `category` attribute in `block.json` to:

```js
"attributes": {
  "category": {
    "type": "integer", // was previously "string"
    "default": 3
  }
}
```

Now we can get that attribute and use it in place of the hard-coded ID passed to `useEntityRecords`:

```js
const { category } = attributes;

const faqs = useEntityRecords( 'postType', 'wceu-faq', { 'wceu-faq-cat': category } );
```

Finally for this stage, let's style the FAQs in the block to look the same as the shortcode version. Add this to `style,scss`:

```css
.wp-block-create-block-wceu-faq-block {
  margin: 12px 0;

  & details {
    background-color: rgb(199, 226, 228); 
    margin-bottom: 8px;
  }

  & details summary {
    padding: 4px;
    color: white;
    background-color: rgb(69, 118, 121); 
  }
  
  & details .faq-content {
    padding: 4px 16px;
  }
}
```