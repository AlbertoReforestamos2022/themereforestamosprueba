import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/texto-imagen', {
	edit: Edit,
	save: Save,
});
