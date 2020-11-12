<template>
  <div>
    <input
      type="hidden"
      name="tags"
      :value="tagsJson"
    >
    <vue-tags-input
      v-model="tag"
      :tags="tags"
      placeholder="タグを5個まで入力できます"
      :autocomplete-items="filteredItems"
      @tags-changed="newTags => tags = newTags"
    />
  </div>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';

export default {
  components: {
    VueTagsInput,
  },
  props: {
    initialTags: {
      type: Array,
      default: [],
    },
    autocompleteItems: {
      type: Array,
      default: [],
    }
  },
  data() {
    return {
      tag: '',
      tags: this.initialTags,
    };
  },
  computed: {
    filteredItems() {
      return this.autocompleteItems.filter(i => {
        return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
      });
    },
    tagsJson() {
      return JSON.stringify(this.tags)
    },
  },
};
</script>
<style lang="css" scoped>
  .vue-tags-input {
    max-width: inherit;
  }
</style>
<style lang="scss">
  .vue-tags-input .ti-tag {
    background: transparent;
    border: 1px solid #747373;
    color: #747373;
    margin-right: 4px;
    border-radius: 0px;
    font-size: 13px;
  }
  .vue-tags-input .ti-tag::before {
    content: "#";
  }

  .ti-input {
  border: 1px solid #bcd537;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  &:focus{
    border-color: #bcd537;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(30, 106, 15, 0.25);
  }
}
</style>