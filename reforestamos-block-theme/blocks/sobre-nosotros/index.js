import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/sobre-nosotros', {
    edit: Edit,
    save: Save,
});
