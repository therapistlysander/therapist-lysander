import * as React from "react"
import { cn } from "@/lib/utils"

interface CheckboxGroupProps {
  values: string[]
  onChange: (values: string[]) => void
  children: React.ReactNode
  className?: string
}

const CheckboxGroup = React.forwardRef<HTMLDivElement, CheckboxGroupProps>(
  ({ values, onChange, children, className }, ref) => {
    const toggle = (value: string) => {
      if (values.includes(value)) {
        onChange(values.filter((v) => v !== value))
      } else {
        onChange([...values, value])
      }
    }

    return (
      <div ref={ref} className={cn("flex flex-col gap-3", className)}>
        {React.Children.map(children, (child) => {
          if (React.isValidElement<CheckboxItemProps>(child)) {
            return React.cloneElement(child, {
              checked: values.includes(child.props.value),
              onChange: () => toggle(child.props.value),
            })
          }
          return child
        })}
      </div>
    )
  }
)
CheckboxGroup.displayName = "CheckboxGroup"

interface CheckboxItemProps {
  value: string
  label: string
  description?: string
  checked?: boolean
  onChange?: () => void
}

const CheckboxItem = React.forwardRef<HTMLButtonElement, CheckboxItemProps>(
  ({ value, label, description, checked, onChange }, ref) => {
    return (
      <button
        ref={ref}
        type="button"
        role="checkbox"
        aria-checked={checked}
        onClick={onChange}
        className={cn(
          "relative flex items-start gap-4 rounded-xl border-2 px-5 py-4 text-left transition-all duration-200 hover:border-primary/30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/40",
          checked
            ? "border-primary bg-teal-light/40 shadow-soft"
            : "border-border bg-card hover:bg-accent/30"
        )}
      >
        <span
          className={cn(
            "mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 transition-all duration-200",
            checked ? "border-primary bg-primary" : "border-stone-light"
          )}
        >
          {checked && (
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
              <path d="M2 6L5 9L10 3" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          )}
        </span>
        <span className="flex flex-col">
          <span className="text-base font-medium text-foreground">{label}</span>
          {description && (
            <span className="mt-1 text-sm text-stone-muted leading-relaxed">{description}</span>
          )}
        </span>
      </button>
    )
  }
)
CheckboxItem.displayName = "CheckboxItem"

export { CheckboxGroup, CheckboxItem }
