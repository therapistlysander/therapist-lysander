import { motion } from "framer-motion"
import { Textarea } from "@/components/ui/textarea"
import { RadioGroup, RadioItem } from "@/components/ui/radio-group"

interface StepExperienceProps {
  data: {
    previousTherapy: string
    currentChallenges: string
  }
  onChange: (data: Partial<StepExperienceProps["data"]>) => void
}

const therapyOptions = [
  { value: "yes-ongoing", label: "Yes, I'm currently in therapy", description: "With this therapist or another professional" },
  { value: "yes-past", label: "Yes, in the past", description: "I've worked with a therapist or coach before" },
  { value: "no", label: "No, this is my first time", description: "I'm completely new to therapy or coaching" },
  { value: "prefer-not", label: "I prefer not to say", description: "We can discuss this in our session instead" },
]

export function StepExperience({ data, onChange }: StepExperienceProps) {
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
          Your experience so far
        </h2>
        <p className="text-stone-muted text-lg leading-relaxed">
          Understanding your background helps me meet you where you are.
        </p>
      </div>

      <div className="space-y-4">
        <label className="block text-base font-medium text-foreground">
          Have you worked with a therapist or coach before?
        </label>
        <RadioGroup
          value={data.previousTherapy}
          onChange={(previousTherapy) => onChange({ previousTherapy })}
        >
          {therapyOptions.map((option) => (
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
          What are your main emotional challenges or goals right now?
        </label>
        <Textarea
          placeholder="I'd like to feel more... / I struggle with..."
          value={data.currentChallenges}
          onChange={(e) => onChange({ currentChallenges: e.target.value })}
        />
        <p className="text-xs text-stone-light">
          A few sentences are enough. This will guide our first conversation.
        </p>
      </div>
    </motion.div>
  )
}
