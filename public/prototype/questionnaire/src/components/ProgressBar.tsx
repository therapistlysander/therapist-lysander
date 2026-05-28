import { motion } from "framer-motion"
import { cn } from "@/lib/utils"

interface ProgressBarProps {
  currentStep: number
  totalSteps: number
}

export function ProgressBar({ currentStep, totalSteps }: ProgressBarProps) {
  const progress = ((currentStep) / totalSteps) * 100

  return (
    <div className="w-full">
      <div className="flex items-center justify-between mb-3">
        <span className="text-xs font-medium uppercase tracking-widest text-stone-muted">
          Step {currentStep} of {totalSteps}
        </span>
        <span className="text-xs font-medium text-primary">{Math.round(progress)}%</span>
      </div>
      <div className="h-1.5 w-full rounded-full bg-border overflow-hidden">
        <motion.div
          className="h-full rounded-full bg-gradient-to-r from-teal to-primary"
          initial={{ width: 0 }}
          animate={{ width: `${progress}%` }}
          transition={{ duration: 0.5, ease: [0.4, 0, 0.2, 1] }}
        />
      </div>
    </div>
  )
}

export function StepIndicator({ currentStep, totalSteps }: ProgressBarProps) {
  return (
    <div className="flex items-center gap-2">
      {Array.from({ length: totalSteps }).map((_, i) => {
        const stepNum = i + 1
        const isActive = stepNum === currentStep
        const isCompleted = stepNum < currentStep

        return (
          <div key={i} className="flex items-center gap-2">
            <motion.div
              className={cn(
                "flex h-8 w-8 items-center justify-center rounded-full text-xs font-medium transition-colors duration-300",
                isActive && "bg-primary text-primary-foreground",
                isCompleted && "bg-teal text-white",
                !isActive && !isCompleted && "bg-border text-stone-muted"
              )}
              animate={{ scale: isActive ? 1.1 : 1 }}
              transition={{ duration: 0.2 }}
            >
              {isCompleted ? (
                <svg width="14" height="14" viewBox="0 0 12 12" fill="none">
                  <path d="M2 6L5 9L10 3" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
              ) : (
                stepNum
              )}
            </motion.div>
            {i < totalSteps - 1 && (
              <div
                className={cn(
                  "h-px w-6 transition-colors duration-300",
                  isCompleted ? "bg-teal" : "bg-border"
                )}
              />
            )}
          </div>
        )
      })}
    </div>
  )
}
