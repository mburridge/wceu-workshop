/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {
	useBlockProps,
	InspectorControls,
	PanelColorSettings,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { useEntityRecords } from '@wordpress/core-data';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
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

	const query = category ? { 'wceu-faq-cat': category } : {};

	const faqs = useEntityRecords( 'postType', 'wceu-faq', query );
	const cats = useEntityRecords( 'taxonomy', 'wceu-faq-cat' );

	const options =
		cats.records &&
		cats.records.map( ( cat ) => {
			return {
				label: cat.name,
				value: cat.id,
			};
		} );

	const onChangecCat = ( val ) =>
		setAttributes( { category: Number( val ) } );
	const onChangeQuestionTextColor = ( val ) =>
		setAttributes( { questionTextColor: val } );
	const onChangeQuestionBackgroundColor = ( val ) =>
		setAttributes( { questionBackgroundColor: val } );
	const onChangeAnswerTextColor = ( val ) =>
		setAttributes( { answerTextColor: val } );
	const onChangeAnswerBackgroundColor = ( val ) =>
		setAttributes( { answerBackgroundColor: val } );

	return (
		<>
			<InspectorControls>
				<PanelBody>
					{ cats.hasResolved && (
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
					) }
				</PanelBody>
				<PanelColorSettings
					title="Question colours"
					colorSettings={ [
						{
							label: 'Text',
							value: questionTextColor,
							onChange: onChangeQuestionTextColor,
						},
						{
							label: 'Background',
							value: questionBackgroundColor,
							onChange: onChangeQuestionBackgroundColor,
						},
					] }
				></PanelColorSettings>
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
			</InspectorControls>
			<div { ...useBlockProps( { style: faqStyles } ) }>
				{ faqs.records &&
					faqs.records.map( ( faq ) => {
						return (
							<details key={ faq.id }>
								<summary>{ faq.title.raw }</summary>
								<section
									className="faq-content"
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
}
