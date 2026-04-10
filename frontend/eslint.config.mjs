// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'

export default withNuxt({
  rules: {
    'vue/no-multiple-template-root': 'off',
    'vue/max-attributes-per-line': 'off'
    '@typescript-eslint/no-explicit-any': 'off',
    '@stylistic/quote-props': ['error', 'as-needed'],
    'operator-linebreak': 'off'
  }
})
