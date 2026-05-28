import { motion } from "framer-motion"
import { Button } from "@/components/ui/button"
import { ArrowRight, Heart, Shield, Sparkles } from "lucide-react"

interface StepWelcomeProps {
  onStart: () => void
}

export function StepWelcome({ onStart }: StepWelcomeProps) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      transition={{ duration: 0.5, ease: [0.4, 0, 0.2, 1] }}
      className="flex flex-col items-center text-center max-w-2xl mx-auto"
    >
      <div className="relative mb-10">
        <div className="absolute inset-0 bg-teal/10 rounded-full blur-3xl scale-150" />
        <img
          src="/images/welcome-illustration.png"
          alt="Calm and welcoming illustration"
          className="relative w-64 h-64 object-cover rounded-3xl shadow-elevated"
        />
      </div>

      <span className="inline-flex items-center gap-2 rounded-full bg-teal-light px-4 py-1.5 text-xs font-medium uppercase tracking-widest text-teal-dark mb-6">
        <Sparkles className="w-3.5 h-3.5" />
        Pre-Intake Questionnaire
      </span>

      <h1 className="text-4xl md:text-5xl font-heading text-foreground mb-6 leading-tight">
        Welcome, and thank you for taking this step
      </h1>

      <p className="text-lg text-stone-muted leading-relaxed mb-10 max-w-lg">
        This short questionnaire helps me understand your needs before our intake session. 
        There are no right or wrong answers — take your time, and share only what feels comfortable.
      </p>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mb-10">
        <div className="flex flex-col items-center gap-3 rounded-2xl bg-card border border-border p-5 shadow-soft">
          <div className="flex h-10 w-10 items-center justify-center rounded-full bg-teal-light">
            <Heart className="h-5 w-5 text-teal" />
          </div>
          <span className="text-sm font-medium text-foreground">3 gentle questions</span>
        </div>
        <div className="flex flex-col items-center gap-3 rounded-2xl bg-card border border-border p-5 shadow-soft">
          <div className="flex h-10 w-10 items-center justify-center rounded-full bg-teal-light">
            <Shield className="h-5 w-5 text-teal" />
          </div>
          <span className="text-sm font-medium text-foreground">Fully confidential</span>
        </div>
        <div className="flex flex-col items-center gap-3 rounded-2xl bg-card border border-border p-5 shadow-soft">
          <div className="flex h-10 w-10 items-center justify-center rounded-full bg-teal-light">
            <Sparkles className="h-5 w-5 text-teal" />
          </div>
          <span className="text-sm font-medium text-foreground">About 5 minutes</span>
        </div>
      </div>

      <Button size="lg" onClick={onStart} className="gap-2">
        Begin Questionnaire
        <ArrowRight className="h-4 w-4" />
      </Button>
    </motion.div>
  )
}
