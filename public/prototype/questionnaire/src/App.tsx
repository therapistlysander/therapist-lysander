import { useState, useCallback } from "react"
import { AnimatePresence, motion } from "framer-motion"
import { Button } from "@/components/ui/button"
import { ProgressBar, StepIndicator } from "@/components/ProgressBar"
import { StepWelcome } from "@/components/StepWelcome"
import { StepAbout } from "@/components/StepAbout"
import { StepExperience } from "@/components/StepExperience"
import { StepPreferences } from "@/components/StepPreferences"
import { StepComplete } from "@/components/StepComplete"
import { ArrowLeft, ArrowRight } from "lucide-react"

type Step = "welcome" | "about" | "experience" | "preferences" | "complete"

interface FormData {
  bringsYouHere: string
  supportAreas: string[]
  previousTherapy: string
  currentChallenges: string
  communicationStyle: string
  expectations: string
  additionalNotes: string
}

const STEP_ORDER: Step[] = ["welcome", "about", "experience", "preferences", "complete"]

const STEP_CONFIG: Record<Exclude<Step, "welcome" | "complete">, { title: string; required: (keyof FormData)[] }> = {
  about: { title: "About You", required: [] },
  experience: { title: "Your Experience", required: [] },
  preferences: { title: "Preferences", required: [] },
}

function App() {
  const [step, setStep] = useState<Step>("welcome")
  const [direction, setDirection] = useState(1)
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [formData, setFormData] = useState<FormData>({
    bringsYouHere: "",
    supportAreas: [],
    previousTherapy: "",
    currentChallenges: "",
    communicationStyle: "",
    expectations: "",
    additionalNotes: "",
  })

  const currentIndex = STEP_ORDER.indexOf(step)
  const questionnaireStep = step === "welcome" ? 0 : step === "complete" ? 4 : currentIndex

  const goToStep = useCallback((targetStep: Step) => {
    const targetIndex = STEP_ORDER.indexOf(targetStep)
    setDirection(targetIndex > currentIndex ? 1 : -1)
    setStep(targetStep)
    window.scrollTo({ top: 0, behavior: "smooth" })
  }, [currentIndex])

  const submitQuestionnaire = useCallback(async (): Promise<boolean> => {
    setIsSubmitting(true)
    try {
      const payload = {
        brings_to_therapy: formData.bringsYouHere || null,
        support_areas: formData.supportAreas.length > 0 ? formData.supportAreas : null,
        previous_therapy: formData.previousTherapy || null,
        presenting_issue: formData.currentChallenges || null,
        communication_style: formData.communicationStyle || null,
        duration_expectation: formData.expectations || null,
        additional_notes: formData.additionalNotes || null,
      }

      const response = await fetch("/api/v1/pre-intake", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: JSON.stringify(payload),
      })

      if (!response.ok) {
        throw new Error("Failed to submit questionnaire")
      }

      return true
    } catch (error) {
      console.error("Submission error:", error)
      return false
    } finally {
      setIsSubmitting(false)
    }
  }, [formData])

  const handleNext = useCallback(async () => {
    const nextIndex = currentIndex + 1
    if (nextIndex < STEP_ORDER.length) {
      if (STEP_ORDER[nextIndex] === "complete") {
        const success = await submitQuestionnaire()
        if (!success) {
          alert("Something went wrong while saving your responses. Please try again.")
          return
        }
      }
      goToStep(STEP_ORDER[nextIndex])
    }
  }, [currentIndex, goToStep, submitQuestionnaire])

  const handleBack = useCallback(() => {
    const prevIndex = currentIndex - 1
    if (prevIndex >= 0) {
      goToStep(STEP_ORDER[prevIndex])
    }
  }, [currentIndex, goToStep])

  const updateFormData = useCallback((data: Partial<FormData>) => {
    setFormData((prev) => ({ ...prev, ...data }))
  }, [])

  const isQuestionnaireStep = step !== "welcome" && step !== "complete"

  return (
    <div className="min-h-screen bg-background font-body">
      {/* Top Navigation */}
      <header className="fixed top-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-md border-b border-border/50">
        <div className="max-w-3xl mx-auto px-6 h-16 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="h-8 w-8 rounded-lg bg-teal flex items-center justify-center">
              <span className="text-white font-heading text-sm font-medium">L</span>
            </div>
            <span className="font-heading text-lg text-foreground hidden sm:block">Therapist Lysander</span>
          </div>
          {isQuestionnaireStep && (
            <StepIndicator currentStep={questionnaireStep} totalSteps={3} />
          )}
        </div>
      </header>

      {/* Main Content */}
      <main className="pt-16">
        <div className="max-w-3xl mx-auto px-6 py-12 md:py-20">
          {/* Progress Bar */}
          {isQuestionnaireStep && (
            <motion.div
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              className="mb-12"
            >
              <ProgressBar currentStep={questionnaireStep} totalSteps={3} />
            </motion.div>
          )}

          {/* Step Content */}
          <AnimatePresence mode="wait" initial={false} custom={direction}>
            {step === "welcome" && (
              <motion.div
                key="welcome"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.4 }}
              >
                <StepWelcome onStart={() => goToStep("about")} />
              </motion.div>
            )}

            {step === "about" && (
              <motion.div
                key="about"
                custom={direction}
                initial={{ opacity: 0, x: direction * 40 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: direction * -40 }}
                transition={{ duration: 0.35, ease: [0.4, 0, 0.2, 1] }}
              >
                <StepAbout data={formData} onChange={updateFormData} />
              </motion.div>
            )}

            {step === "experience" && (
              <motion.div
                key="experience"
                custom={direction}
                initial={{ opacity: 0, x: direction * 40 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: direction * -40 }}
                transition={{ duration: 0.35, ease: [0.4, 0, 0.2, 1] }}
              >
                <StepExperience data={formData} onChange={updateFormData} />
              </motion.div>
            )}

            {step === "preferences" && (
              <motion.div
                key="preferences"
                custom={direction}
                initial={{ opacity: 0, x: direction * 40 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: direction * -40 }}
                transition={{ duration: 0.35, ease: [0.4, 0, 0.2, 1] }}
              >
                <StepPreferences data={formData} onChange={updateFormData} />
              </motion.div>
            )}

            {step === "complete" && (
              <motion.div
                key="complete"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 0.5 }}
              >
                <StepComplete onBook={() => window.open("https://calendly.com", "_blank")} />
              </motion.div>
            )}
          </AnimatePresence>

          {/* Navigation Buttons */}
          {isQuestionnaireStep && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.2 }}
              className="flex items-center justify-between mt-14 pt-8 border-t border-border"
            >
              <Button
                variant="ghost"
                onClick={handleBack}
                className="gap-2 text-stone-muted hover:text-foreground"
              >
                <ArrowLeft className="h-4 w-4" />
                Back
              </Button>

              <Button onClick={handleNext} className="gap-2" disabled={isSubmitting}>
                {isSubmitting ? "Saving..." : step === "preferences" ? "Complete" : "Continue"}
                {!isSubmitting && <ArrowRight className="h-4 w-4" />}
              </Button>
            </motion.div>
          )}
        </div>
      </main>

      {/* Footer */}
      <footer className="border-t border-border/50 py-8">
        <div className="max-w-3xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-light">
          <p>Your responses are confidential and secure.</p>
          <p>Therapist Lysander &middot; Pre-Intake Questionnaire</p>
        </div>
      </footer>
    </div>
  )
}

export default App
