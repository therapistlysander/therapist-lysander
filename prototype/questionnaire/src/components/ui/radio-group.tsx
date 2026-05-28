import * as React from "react"
import { cn } from "@/lib/utils"

interface RadioGroupProps {
  value: string
  onChange: (value: string) => void
  children: React.ReactNode
  className?: string
}

const RadioGroup = React.forwardRef<HTMLDivElement, RadioGroupProps>(
  ({ value, onChange, children, className }, ref) => {
    return (
      <div ref={ref} className={cn("flex flex-col gap-3", className)} role="radiogroup">
        {React.Children.map(children, (child) => {
          if (React.isValidElement<RadioItemProps>(child)) {
            return React.cloneElement(child, {
              checked: child.props.value === value,
              onChange: () => onChange(child.props.value),
            })
          }
          return child
        })}
      </div>
    )
  }
)
RadioGroup.displayName = "RadioGroup"

interface RadioItemProps {
  value: string
  label: string
  description?: string
  checked?: boolean
  onChange?: () => void
}

const RadioItem = React.forwardRef<HTMLButtonElement, RadioItemProps>(
  ({ value, label, description, checked, onChange }, ref) => {
    return (
      <button
        ref={ref}
        type="button"
        role="radio"
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
            "mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-200",
            checked ? "border-primary bg-primary" : "border-stone-light"
          )}
        >
          {checked && <span className="h-2 w-2 rounded-full bg-white" />}
        </span>
        <span className="flex flex-col">
          <span className={cn("text-base font-medium", checked ? "text-foreground" : "text-foreground")}>
            {label}
          </span>
          {description && (
            <span className="mt-1 text-sm text-stone-muted leading-relaxed">{description}</span>
          )}
        </span>
      </button>
    )
  }
)
RadioItem.displayName = "RadioItem"

export { RadioGroup, RadioItem }
