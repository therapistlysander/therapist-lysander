import { motion } from "framer-motion"
import { Textarea } from "@/components/ui/textarea"
import { CheckboxGroup, CheckboxItem } from "@/components/ui/checkbox-group"

interface StepAboutProps {
  data: {
    bringsYouHere: string
    supportAreas: string[]
  }
  onChange: (data: Partial<StepAboutProps["data"]>) => void
}

const supportOptions = [
  { value: "trauma", label: "Trauma or PTSD", description: "Processing past experiences that still affect you" },
  { value: "anxiety", label: "Anxiety or worry", description: "Persistent anxiety, panic, or overwhelming thoughts" },
  { value: "depression", label: "Low mood or depression", description: "Feeling stuck, numb, or lacking motivation" },
  { value: "self-worth", label: "Self-worth or identity", description: "Questions about who you are and your value" },
  { value: "relationships", label: "Relationships", description: "Challenges with partners, family, or connections" },
  { value: "life-transition", label: "Life transition", description: "Major changes like relocation, career, or loss" },
  { value: "burnout", label: "Burnout or stress", description: "Feeling emotionally or physically exhausted" },
  { value: "other", label: "Something else", description: "A concern not listed here" },
]

export function StepAbout({ data, onChange }: StepAboutProps) {
  return (
    <motion.div
      initial={{ opacity: 0, x: 30 }}
      animate={{ opacity: 1, x: 0 }}
      exit={{ opacity: 0, x: -30 }}
      transition={{ duration: 0.4, ease: [0.4, 0, 0.2, 1] }}
      className="space-y-10"
    >
      <div className="space-y-3">
        <h2 className="text-3xl md:text-4xl font-heading text-foreground">
          What brings you here today?
        </h2>
        <p className="text-stone-muted text-lg leading-relaxed">
          Share as much or as little as you like. This helps me understand where you're starting from.
        </p>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          In your own words, what led you to reach out?
        </label>
        <Textarea
          placeholder="I've been feeling..."
          value={data.bringsYouHere}
          onChange={(e) => onChange({ bringsYouHere: e.target.value })}
        />
        <p className="text-xs text-stone-light">
          This is optional — you can also select from the options below.
        </p>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          What areas would you like support with?
          <span className="text-stone-light font-normal ml-1">(Select all that apply)</span>
        </label>
        <CheckboxGroup
          values={data.supportAreas}
          onChange={(supportAreas) => onChange({ supportAreas })}
        >
          {supportOptions.map((option) => (
            <CheckboxItem
              key={option.value}
              value={option.value}
              label={option.label}
              description={option.description}
            />
          ))}
        </CheckboxGroup>
      </div>
    </motion.div>
  )
}
