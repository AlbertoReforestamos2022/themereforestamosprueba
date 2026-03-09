import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/logos-aliados', {
    edit: Edit,
    save: Save,
});
