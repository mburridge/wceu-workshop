## Section 4

In this section you'll make the category user selectable.

Ideally, there should be no default category, i.e. get all the FAQs, so set the default to 0 in `block.json`

```js
"attributes": {
  "category": {
    "type": "integer",
    "default: 0
  }
}
```

We'll use a conditional to add the query object, like we did in `render.php`:

```js
const { category } = attributes;

const query = category ? { "wceu-faq-cat": category } : {};

const faqs = useEntityRecords("postType", "wceu-faq", query);
```

Now if the category in `block.json` is 0, i.e. the default, all the FAQs show, otherwise it shows the FAQs from the selected category.

We can now move on to making the category user selectable.

We're going to add some Inspector Controls so, as a component can only return one root level element, let's add an empty element as a wrapper:

```js
return (
  <>
    <div {...useBlockProps()}>
      {faqs.records &&
        faqs.records.map((faq) => {
          return (
            <details key={faq.id}>
              <summary>{faq.title.raw}</summary>
              <section
                className="faq-content"
                dangerouslySetInnerHTML={{
                  __html: faq.content.raw,
                }}
              />
            </details>
          );
        })}
    </div>
  </>
);
```

Import the `InspectorControls` component, and also the `PanelBody` and `SelectControl` components.

```js
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl } from "@wordpress/components";
```

Add these to the JSX

```js
<InspectorControls>
  <PanelBody>
    <SelectControl
      label={"Category"}
      onChange={onChangecCat}
      value={category}
      options={[
        {
          label: "All",
          value: 0,
        },
        {
          label: "Athens",
          value: 4,
        },
        {
          label: "Vienna",
          value: 3,
        },
      ]}
    />
  </PanelBody>
</InspectorControls>
```

Then we need the `onChangeCat` handler function:

```js
const onChangecCat = (val) => setAttributes({ category: Number(val) });
```

As the attribute is of `type: integer` we need to cast the value from the SelectControl to a Number.

The handler function also uses `setAttributes` so that also needs to be destructured from the object passed to the `Edit()` function:

```js
export default function Edit( { attributes, setAttributes } ) {
```

This works, and if you change the category the attribute updates and the front end will reflect that, but at the moment we're using hard-coded IDs, so let's fetch the categories from the database and take a look at what we get back:

```js
const cats = useEntityRecords("taxonomy", "wceu-faq-cat");
console.log(cats);
```

You'll see that once `hasResolved` is true the `records` property has an array containing the `wceu-faq-cat`custom taxonomy terms.

Create an array that will replace the hard-coded version:

```js
const options =
  cats.records &&
  cats.records.map((cat) => {
    return {
      label: cat.name,
      value: cat.id,
    };
  });
```

We need to check for `cats.records` as might not yet be populated.

Then we can replace the static options with:

```js
{
  cats.hasResolved && (
    <SelectControl
      label={"Category"}
      onChange={onChangecCat}
      value={category}
      options={[
        {
          label: "All",
          value: 0,
        },
        ...options,
      ]}
    />
  );
}
```

Again we need to check for `cats.hasResolved`. We also add an option for 'All'.
