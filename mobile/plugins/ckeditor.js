import Vue from 'vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import '@ckeditor/ckeditor5-build-classic/build/translations/ar';
import '@ckeditor/ckeditor5-build-classic/build/translations/fr';
import VueCkeditor from 'vue-ckeditor5'
 
const options = {
  	editors: {
    	classic: ClassicEditor,
  	},
	name: 'ckeditor'
}
 
Vue.use(VueCkeditor.plugin, options);