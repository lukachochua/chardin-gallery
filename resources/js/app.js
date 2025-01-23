import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('alpine:init', () => {
    Alpine.data('categoryForm', () => ({
        parentCategoryId: {{ old('parent_id', $parentCategories-> first() ? -> id) ?? 'null'
}},
    childCategories: [],

    init() {
    // Automatically fetch child categories if a parent category is already selected
    if(this.parentCategoryId) {
    this.fetchChildCategories();
}
        },

        async fetchChildCategories() {
    if (!this.parentCategoryId) {
        this.childCategories = [];
        return;
    }

    console.log('Fetching child categories for parent ID:', this.parentCategoryId);

    const response = await fetch(
        `/admin/api/categories/${this.parentCategoryId}/children`);
    const data = await response.json();

    console.log('Child categories:', data);

    this.childCategories = data.map(category => ({
        id: category.id,
        name: category.name
    }));
}
    }));
});