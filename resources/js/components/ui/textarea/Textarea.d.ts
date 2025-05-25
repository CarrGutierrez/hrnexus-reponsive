import type { HTMLAttributes } from 'vue'

export interface TextareaProps extends HTMLAttributes {
  modelValue?: string
  rows?: number
  placeholder?: string
  required?: boolean
  class?: string
}

export interface TextareaEmits {
  (e: 'update:modelValue', value: string): void
}

export declare const Textarea: import('vue').DefineComponent<TextareaProps, {}, {}, {}, {}, import('vue').ComponentOptionsMixin, TextareaEmits> 