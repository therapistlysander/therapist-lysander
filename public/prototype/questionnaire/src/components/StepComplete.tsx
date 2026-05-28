import { motion } from "framer-motion"
import { Button } from "@/components/ui/button"
import { Calendar, ArrowRight, CheckCircle2 } from "lucide-react"

interface StepCompleteProps {
  onBook: () => void
}

export function StepComplete({ onBook }: StepCompleteProps) {
  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ duration: 0.5, ease: [0.4, 0, 0.2, 1] }}
      className="flex flex-col items-center text-center max-w-2xl mx-auto"
    >
      <div className="relative mb-10">
        <div className="absolute inset-0 bg-teal/10 rounded-full blur-3xl scale-150" />
        <img
          src="/images/success-illustration.png"
          alt="Peaceful botanical illustration"
          className="relative w-56 h-56 object-cover rounded-3xl shadow-elevated"
        />
      </div>

      <motion.div
        initial={{ scale: 0 }}
        animate={{ scale: 1 }}
        transition={{ delay: 0.2, type: "spring", stiffness: 200, damping: 15 }}
        className="flex h-14 w-14 items-center justify-center rounded-full bg-teal text-white mb-6"
      >
        <CheckCircle2 className="h-7 w-7" />
      </motion.div>

      <h1 className="text-4xl md:text-5xl font-heading text-foreground mb-6 leading-tight">
        Thank you for sharing
      </h1>

      <p className="text-lg text-stone-muted leading-relaxed mb-4 max-w-lg">
        Your responses have been received. I appreciate the openness you've shown — 
        it will help me prepare for our time together.
      </p>

      <p className="text-base text-stone-muted leading-relaxed mb-10 max-w-md">
        The next step is to schedule your intake session. This is where we'll explore 
        your story in more depth and discuss how we might work together.
      </p>

      <div className="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
        <Button size="lg" onClick={onBook} className="gap-2">
          <Calendar className="h-4 w-4" />
          Schedule Intake Session
          <ArrowRight className="h-4 w-4" />
        </Button>
        <Button size="lg" variant="outline" onClick={() => window.print()}>
          Save a Copy
        </Button>
      </div>

      <div className="mt-12 rounded-2xl bg-teal-light/50 border border-teal/10 p-6 max-w-md">
        <p className="text-sm text-teal-dark leading-relaxed">
          <strong className="font-medium">What happens next?</strong>
          <br />
          After booking, you'll receive a confirmation email with session details. 
          If you have any questions beforehand, feel free to reach out.
        </p>
      </div>
    </motion.div>
  )
}
