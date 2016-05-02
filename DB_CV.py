# -*- coding: utf-8 -*-
"""
Created on Sat Apr 23 12:51:05 2016

@author: Maitri
"""

import pandas as p
import numpy as np
import matplotlib as plt
from sklearn.cross_validation import cross_val_score
import sklearn.linear_model as lm
import sklearn.ensemble as rm
from sklearn.svm import SVR
from sklearn.metrics import accuracy_score

dataset = p.read_csv("dbProjection.csv")


print "loading data.."

traindata = p.read_csv("train.csv")
testdata = p.read_csv("test.csv")
y = p.read_csv("train2.csv")


                             
#r = rm.RandomForestClassifier(n_estimators=10, criterion='gini', max_depth=10, min_samples_split=2, min_samples_leaf=1, min_weight_fraction_leaf=0.0, max_features=2, max_leaf_nodes=None, bootstrap=True, oob_score=False, n_jobs=1, random_state=None, verbose=0, warm_start=False, class_weight=None)

r = rm.AdaBoostClassifier(base_estimator=None, n_estimators=50, learning_rate=1.0, algorithm='SAMME.R', random_state=None)
X_all = traindata+testdata   
X = traindata                         
lentrain = len(traindata)
X = X[:lentrain]

#X = X_all[:lentrain]
X_test = X_all[lentrain:]

print "training on full data"
r.fit(X,y)
print "done fitting"
pred = r.predict(testdata)

#print "20 Fold CV Score: ", np.mean(cross_validation.cross_val_score(r, X, y, cv=20, scoring='roc_auc'))

#testfile = p.read_csv('test.csv')
pred_df = p.DataFrame(pred, columns=['availability'])
#pred_df.to_csv('benchmark.csv')

#pred_df = p.DataFrame(pred, columns=['availability'])
pred_df.to_csv('Adaboost.csv')


print "submission file created.."

#scores = cross_val_score(r,X,y)
#print " Cross validation score: ",scores.mean()         

Accuracy = accuracy_score(y, pred)
print " Accuracy = ", Accuracy                 