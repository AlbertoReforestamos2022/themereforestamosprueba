import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/faqs', {
	edit: Edit,
	save: Save,
});
