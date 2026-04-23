import js from '@eslint/js'
import globals from 'globals'
import reactHooks from 'eslint-plugin-react-hooks'
import reactRefresh from 'eslint-plugin-react-refresh'
import tseslint from 'typescript-eslint'
import { globalIgnores } from 'eslint/config'

export default tseslint.config([
    globalIgnores([
        'dist',
        'build',
        'public',
        'node_modules',
        'vendor',
        '**/*.min.js'
    ]),

    js.configs.recommended,
    ...tseslint.configs.recommended,

    {
        files: ['**/*.{ts,tsx}'],

        languageOptions: {
            ecmaVersion: 2020,
            globals: globals.browser,
        },

        plugins: {
            'react-hooks': reactHooks,
            'react-refresh': reactRefresh,
        },

        rules: {
            ...reactHooks.configs.recommended.rules,
            '@typescript-eslint/no-explicit-any': 'off',
            "@typescript-eslint/no-unused-vars": [
                "off",
                {
                    argsIgnorePattern: "^_",
                    varsIgnorePattern: "^_",
                },
            ],
            "react-hooks/set-state-in-effect": "off",
            "react-hooks/exhaustive-deps": "off",
            "@typescript-eslint/ban-ts-comment": "off",
            "react-refresh/only-export-components": "off",
            "prefer-const": "off",
            "no-constant-binary-expression": "off",
            "react-hooks/rules-of-hooks": "off"
        },
    },
])