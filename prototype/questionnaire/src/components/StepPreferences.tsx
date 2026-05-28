import { motion } from "framer-motion"
import { Textarea } from "@/components/ui/textarea"
import { RadioGroup, RadioItem } from "@/components/ui/radio-group"

interface StepPreferencesProps {
  data: {
    communicationStyle: string
    expectations: string
    additionalNotes: string
  }
  onChange: (data: Partial<StepPreferencesProps["data"]>) => void
}

const communicationOptions = [
  { value: "gentle", label: "Gentle and supportive", description: "I prefer a soft, empathetic approach with lots of encouragement" },
  { value: "direct", label: "Direct and structured", description: "I appreciate clear guidance, frameworks, and honest feedback" },
  { value: "collaborative", label: "Collaborative", description: "I like to be actively involved in setting the direction" },
  { value: "flexible", label: "Flexible and intuitive", description: "I'm open to going with what feels right in the moment" },
]

const expectationOptions = [
  { value: "short-term", label: "Short-term support", description: "A few sessions to work through something specific" },
  { value: "medium-term", label: "Medium-term work", description: "Several weeks or months of regular sessions" },
  { value: "long-term", label: "Long-term therapy", description: "Ongoing support for deeper or complex concerns" },
  { value: "exploring", label: "I'm still exploring", description: "Not sure yet — I'd like to discuss this with you" },
]

export function StepPreferences({ data, onChange }: StepPreferencesProps) {
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
          Preferences & goals
        </h2>
        <p className="text-stone-muted text-lg leading-relaxed">
          Help me understand how you work best and what you're hoping for.
        </p>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          What communication style do you prefer in sessions?
        </label>
        <RadioGroup
          value={data.communicationStyle}
          onChange={(communicationStyle) => onChange({ communicationStyle })}
        >
          {communicationOptions.map((option) => (
            <RadioItem
              key={option.value}
              value={option.value}
              label={option.label}
              description={option.description}
            />
          ))}
        </RadioGroup>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          What are your expectations for the duration of our work together?
        </label>
        <RadioGroup
          value={data.expectations}
          onChange={(expectations) => onChange({ expectations })}
        >
          {expectationOptions.map((option) => (
            <RadioItem
              key={option.value}
              value={option.value}
              label={option.label}
              description={option.description}
            />
          ))}
        </RadioGroup>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          Is there anything else you'd like me to know before our intake session?
        </label>
        <Textarea
          placeholder="Triggers, preferences, accessibility needs, or anything that would help you feel safe..."
          value={data.additionalNotes}
          onChange={(e) => onChange({ additionalNotes: e.target.value })}
        />
        <p className="text-xs text-stone-light">
          Completely optional. This space is yours.
        </p>
      </div>
    </motion.div>
  )
}
