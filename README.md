Moodle multiple-choice question type with 'false' penalty for selected incorrect choice
----------------------------------------------------------------------------------------

This is a multiple-choice, multiple-response question type that integrates a 'false' penalty for any selected
incorrect choice,

This version can be used with Moodle 3.0 to 3.6 versions.

The official git repository of this question type is now https://github.com/Okidoki72/moodle-qtype_mchoicef

### Description:

The multiple-choice question type with 'false' penalty is adapted from the existing multichoice question.
The main difference from the standard Moodle multiple choice question type is
in the way that grading works.
The teacher editing interface is slightly modified as when creating the question, the teacher just
indicates which choices are correct.

### Grading:

In an multiple-choice question type with 'false' penalty, a respondent can choose one or more answers.
The evaluation policy is the following:
* If #correct is the number of correct options,
* and #incorrect the number of incorrect options,
* 1/#correct of the points are given for each correct choice selected,
* 1/#incorrect of the points are substracted for each incorrect choice selected,
* The minimum score is 0

**Example 1:**
(Question: 10 points) Albert Einstein:
- [ ] was soccer player
- [x] was a theoretical physicist
- [x] developed the theory of relativity
- [x] is best known for for his mass–energy equivalence formula E=mc^2
- [ ] received the Nobel Prize
- [x] was a musicologist
- [ ] is best known for his contributions to the science of evolution

There are 4 correct options (#correct = 4)  and 3 incorrect options (#incorrect = 3).
Since there are 3 correct options selected and 1 incorrect option selected, the score is:

[(3/#correct) – (1/#incorrect)] x 10 = 4.16 points.

**Example 2:**
(Question: 10 points) Albert Einstein:
- [x] was a theoretical physicist
- [x] developed the theory of relativity
- [x] is best known for for his mass–energy equivalence formula E=mc^2
- [ ] received the Nobel Prize
- [x] was a musicologist

There are 4 correct options (#correct = 4)  and 1 incorrect options (#incorrect = 1).
Since there are 3 correct options selected and 1 incorrect option selected, the score is:

[(3/#correct) – (1/#incorrect)] x 10 = 0 points.

Before using this questiontype, teachers must really think if this grading is what they want.

### Installation

Download the zip from:
  https://github.com/Okidoki72/moodle-qtype_mchoicef/archive/master.zip

In Moodle, go to [Site administration > Plugins > Install plugins], drag an drop the zip file and click the 'Install plugin from the ZIP file' button, and follow the instructions.
