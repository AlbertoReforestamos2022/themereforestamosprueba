import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/galeria-tabs', {
    edit: Edit,
    save: Save,
});
